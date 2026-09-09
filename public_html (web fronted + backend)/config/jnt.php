<?php

return [
    'env' => env('JNT_ENV', 'testing'),

    // Order & Cancellation
    'key' => env('JNT_ORDER_KEY', 'AKe62df84bJ3d8e4b1hea2R45j11klsb'),
    'username' => env('JNT_USERNAME', 'SUB-PT-WOWINFOOD'),
    'api_key' => env('JNT_API_KEY', 'ZK29NP'),
    'order_endpoint' => env('JNT_ORDER_ENDPOINT', 'https://demo-ecommerce.inuat-jntexpress.id/jts-idn-ecommerce-api/api/order/create'),
    'cancel_endpoint' => env('JNT_CANCEL_ENDPOINT', 'https://demo-ecommerce.inuat-jntexpress.id/jts-idn-ecommerce-api/api/order/cancel'),

    // Track
    'track_endpoint' => env('JNT_TRACK_ENDPOINT', 'https://demo-general.inuat-jntexpress.id/jandt_track/track/trackAction!tracking.action'),
    'track_password' => env('JNT_TRACK_PASSWORD', 'MKuW68n0zjsm'),

    // Tariff Check
    'tariff_endpoint' => env('JNT_TARIFF_ENDPOINT', 'https://demo-general.inuat-jntexpress.id/jandt_track/inquiry.action'),
    'tariff_key' => env('JNT_TARIFF_KEY', 'MKuW68n0zjsm'),

    // Default Shipper (Kantor Pusat / Gudang Wowin)
    'shipper' => [
        'name' => env('JNT_SHIPPER_NAME', 'PT WOWIN PURNOMO PUTERA'),
        'contact' => env('JNT_SHIPPER_CONTACT', 'William Purnomo'),
        'phone' => env('JNT_SHIPPER_PHONE', '081216301220'),
        'address' => env('JNT_SHIPPER_ADDRESS', 'Jl. Raya No. KM 07, Duwet, Ngetal, Kec. Pogalan, Kab. Trenggalek, Jawa Timur 66371'),
        'origin_code' => env('JNT_SHIPPER_ORIGIN_CODE', 'SUB'),
        'zip' => env('JNT_SHIPPER_ZIP', '66371'),
    ],

    // VIP J&T Jawara Flat Rate Settings
    'vip_jawara_jatim_madura' => (int) env('JNT_VIP_JAWARA_JATIM_MADURA', 4500), // Rp 4.500 / Kg (Jatim + Madura)
    'vip_jawara_jawa_non_jatim' => (int) env('JNT_VIP_JAWARA_JAWA_NON_JATIM', 9500), // Rp 9.500 / Kg (Pulau Jawa lainnya)
    'vip_flat_rate_jawa' => (int) env('JNT_VIP_FLAT_RATE_JAWA', 4500), // Fallback
    'regular_rate_luar_jawa' => (int) env('JNT_REGULAR_RATE_LUAR_JAWA', 25000), // Luar Pulau Jawa
];
