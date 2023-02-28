<?php

return [
    'version' => explode('@', \PackageVersions\Versions::getVersion('tannhatcms/ophim-core') ?? 0)[0],
    'episodes' => [
        'types' => [
            'embed' => 'Nhúng',
            'mp4' => 'MP4',
            'server1' => 'Máy chủ 1',
            'm3u8' => 'M3U8'
        ]
    ],
    'ckfinder' => [
        'loadRoutes' => false,
        'backends' => [
            'name'         => 'default',
            'adapter'      => 'local',
            'baseUrl'      => '/storage/',
            'root'         => public_path('/storage/'),
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8'
        ]
    ]
];