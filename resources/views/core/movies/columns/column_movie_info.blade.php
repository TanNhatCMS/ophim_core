@php
$name = data_get($entry, $column['name']);
$origin_name = data_get($entry, $column['origin_name']);
$publish_year = data_get($entry, $column['publish_year']);
$episode_current = data_get($entry, $column['episode_current']);
$status = data_get($entry, $column['status']);
$movie_type = data_get($entry, $column['movie_type']);
$thumb_url = data_get($entry, $column['thumb_url']);
$config_show_type = [
    'single' => [
        'class' => 'bg-secondary',
        'label' => 'Phim lẻ',
    ],
    'series' => [
        'class' => 'bg-primary',
        'label' => 'Phim bộ',
    ],
];
$config_show_status = [
    'trailer' => [
        'class' => 'bg-warning',
        'label' => 'Trailer',
    ],
    'ongoing' => [
        'class' => 'bg-info',
        'label' => 'Đang chiếu',
    ],
    'completed' => [
        'class' => 'bg-success',
        'label' => 'Hoàn thành',
    ],
];
@endphp
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
    <div class="card mb-3" style=" max-width: 350px; height:250px; ">
        <div  style="display: flex;" >
            <div class="col-md-4">
                <img src="{{ $thumb_url }}" height="100px" width="68px"/>
            </div>
            <div class="col-md-8">
                <div class="card-body" >
                    <h5 class="card-title text-primary pb-2">{{ $name }}</h5>
                    <h6 class="card-title text-muted pb-2">({{ $origin_name }})</h6>
                    <p class="card-text">
                        <span class="text-success">[{{ $publish_year }}]</span>
                        <span class="text-danger">[{{ $episode_current }}]</span>
                    </p>
                    <p class="card-text">
                        <div class="badge {{ $config_show_type[$movie_type]['class'] }} font-weight-normal">{{ $config_show_type[$movie_type]['label'] }}</div>
                        <div class="badge {{ $config_show_status[$status]['class'] }} font-weight-normal">{{ $config_show_status[$status]['label'] }}</div>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
