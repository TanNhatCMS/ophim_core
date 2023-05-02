<?php

namespace Ophim\Core\Models;

use Backpack\Settings\app\Models\Setting;
use Illuminate\Support\Facades\URL as LARURL;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Ophim\Core\Models\Actor;
use Ophim\Core\Models\Catalog;
use Ophim\Core\Models\Category;
use Ophim\Core\Models\Director;
use Ophim\Core\Models\Movie;
use Ophim\Core\Models\Episode;
use Ophim\Core\Models\Region;
use Ophim\Core\Models\Studio;
use Ophim\Core\Models\Tag;
use Prologue\Alerts\Facades\Alert;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

class SiteMaps 
{


    private static function renderStyles()
    {
        $xml = view('ophim::sitemap/styles', [
            'title' => Setting::get('site_homepage_title'),
            'domain' => LARURL::to('/')
        ])->render();

        file_put_contents(public_path('main-sitemap.xsl'), $xml);
        return;
    }

    private static function addStyles($fileName)
    {
        $path = public_path($fileName);
        if(file_exists($path)) {
            $content = file_get_contents($path);
            $content = str_replace('?'.'>', '?'.'>'.'<'.'?'.'xml-stylesheet type="text/xsl" href="'. LARURL::to('/') .'/main-sitemap.xsl"?'.'>', $content);
            file_put_contents($path, $content);
        }
    }

    public static function updateSitemap($ping = false, $alert = false)
    {
        self::renderStyles();
        if (!File::isDirectory('sitemap')){
            File::makeDirectory('sitemap', 0777, true, true);
        }
        
        $sitemapIndex = SitemapIndex::create();

        $sitemapPage = Sitemap::create();
        $sitemapPage->add(Url::create('/')
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_HOURLY)
            ->setPriority(1));
        Catalog::chunkById(100, function ($catalogs) use ($sitemapPage) {
            foreach ($catalogs as $catalog) {
                $sitemapPage->add(
                    Url::create($catalog->getUrl())
                        ->setLastModificationDate(now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.9)
                );
            }
        });
        $sitemapPage->writeToFile(public_path('sitemap/catalogs-sitemap.xml'));
        self::addStyles('sitemap/catalogs-sitemap.xml');
        $sitemapIndex->add('sitemap/catalogs-sitemap.xml');

        $sitemapCategories = Sitemap::create();
        Category::chunkById(100, function ($categoires) use ($sitemapCategories) {
            foreach ($categoires as $category) {
                $sitemapCategories->add(
                    Url::create($category->getUrl())
                        ->setLastModificationDate(now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.8)
                );
            }
        });
        $sitemapCategories->writeToFile(public_path('sitemap/categories-sitemap.xml'));
        self::addStyles('sitemap/categories-sitemap.xml');
        $sitemapIndex->add('sitemap/categories-sitemap.xml');

        $sitemapRegions = Sitemap::create();
        Region::chunkById(100, function ($regions) use ($sitemapRegions) {
            foreach ($regions as $region) {
                $sitemapRegions->add(
                    Url::create($region->getUrl())
                        ->setLastModificationDate(now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.8)
                );
            }
        });
        $sitemapRegions->writeToFile(public_path('sitemap/regions-sitemap.xml'));
        self::addStyles('sitemap/regions-sitemap.xml');
        $sitemapIndex->add('sitemap/regions-sitemap.xml');

        $chunk = 0;
        Movie::chunkById(200, function ($movies) use ($sitemapIndex, &$chunk) {
            $chunk++;
            $sitemapMovies = null;
            $sitemapMovies = Sitemap::create();
            foreach ($movies as $movie) {
                $sitemapMovies->add(
                    Url::create($movie->getUrl())
                        ->setLastModificationDate($movie->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.7)
                );
            }
            $sitemapMovies->writeToFile(public_path("sitemap/movies-sitemap{$chunk}.xml"));
            self::addStyles("sitemap/movies-sitemap{$chunk}.xml");
            $sitemapIndex->add("sitemap/movies-sitemap{$chunk}.xml");
        });
        $chunk = 0;
        Episode::chunkById(200, function ($episodes) use ($sitemapIndex, &$chunk) {
            $chunk++;
            $sitemapEpisodes = null;
            $sitemapEpisodes = Sitemap::create();
            foreach ($episodes as $episode) {
                $sitemapEpisodes ->add(
                    Url::create($episode->getUrl())
                        ->setLastModificationDate($episode->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.7)
                );
            }
            $sitemapEpisodes ->writeToFile(public_path("sitemap/episodes-sitemap{$chunk}.xml"));
            self::addStyles("sitemap/episodes-sitemap{$chunk}.xml");
            $sitemapIndex->add("sitemap/episodes-sitemap{$chunk}.xml");
        });

        $sitemapIndex->writeToFile(public_path('sitemap.xml'));
        self::addStyles("sitemap.xml");
        if($ping == true && $alert == true){
            $status = ping_sitemap( url('/sitemap.xml'))."& ".ping_pingomatic( url(""), Setting::get('site_homepage_title'));
            Alert::success("Đã tạo thành công sitemap tại thư mục public & ".$status)->flash();
        }elseif($ping == false && $alert == true){
            Alert::success("Đã tạo thành công sitemap tại thư mục public")->flash();
        }elseif($ping ==true  && $alert == false){
            return ping_sitemap( url('/sitemap.xml'))."& ".ping_pingomatic( url(""), Setting::get('site_homepage_title'));
        }
        return;
    }
}