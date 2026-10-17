<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\Village;
use Illuminate\Database\Seeder;

class IndonesiaRegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            [
                'code' => '31',
                'name' => 'DKI Jakarta',
                'cities' => [
                    [
                        'name' => 'Jakarta Selatan',
                        'type' => 'Kota',
                        'latitude' => -6.2615,
                        'longitude' => 106.8106,
                        'districts' => [
                            [
                                'name' => 'Tebet',
                                'latitude' => -6.2372,
                                'longitude' => 106.8524,
                                'villages' => [
                                    ['name' => 'Tebet Barat', 'postal_code' => '12810', 'latitude' => -6.2385, 'longitude' => 106.8495],
                                    ['name' => 'Tebet Timur', 'postal_code' => '12820', 'latitude' => -6.2360, 'longitude' => 106.8560],
                                    ['name' => 'Menteng Dalam', 'postal_code' => '12870', 'latitude' => -6.2295, 'longitude' => 106.8420],
                                    ['name' => 'Kebon Baru', 'postal_code' => '12830', 'latitude' => -6.2410, 'longitude' => 106.8620],
                                    ['name' => 'Bukit Duri', 'postal_code' => '12840', 'latitude' => -6.2220, 'longitude' => 106.8580],
                                ],
                            ],
                            [
                                'name' => 'Kebayoran Baru',
                                'latitude' => -6.2442,
                                'longitude' => 106.8006,
                                'villages' => [
                                    ['name' => 'Senayan', 'postal_code' => '12190', 'latitude' => -6.2280, 'longitude' => 106.8050],
                                    ['name' => 'Selong', 'postal_code' => '12110', 'latitude' => -6.2340, 'longitude' => 106.8020],
                                    ['name' => 'Melawai', 'postal_code' => '12160', 'latitude' => -6.2440, 'longitude' => 106.7990],
                                    ['name' => 'Gandaria Utara', 'postal_code' => '12140', 'latitude' => -6.2550, 'longitude' => 106.7920],
                                    ['name' => 'Rawa Barat', 'postal_code' => '12180', 'latitude' => -6.2390, 'longitude' => 106.8150],
                                ],
                            ],
                            [
                                'name' => 'Cilandak',
                                'latitude' => -6.2932,
                                'longitude' => 106.7975,
                                'villages' => [
                                    ['name' => 'Cilandak Barat', 'postal_code' => '12430', 'latitude' => -6.2950, 'longitude' => 106.7940],
                                    ['name' => 'Cipete Selatan', 'postal_code' => '12410', 'latitude' => -6.2780, 'longitude' => 106.8040],
                                    ['name' => 'Lebak Bulus', 'postal_code' => '12440', 'latitude' => -6.3050, 'longitude' => 106.7830],
                                    ['name' => 'Pondok Labu', 'postal_code' => '12450', 'latitude' => -6.3150, 'longitude' => 106.7950],
                                ],
                            ],
                            [
                                'name' => 'Pasar Minggu',
                                'latitude' => -6.2891,
                                'longitude' => 106.8342,
                                'villages' => [
                                    ['name' => 'Pejaten Barat', 'postal_code' => '12510', 'latitude' => -6.2750, 'longitude' => 106.8290],
                                    ['name' => 'Pejaten Timur', 'postal_code' => '12510', 'latitude' => -6.2850, 'longitude' => 106.8450],
                                    ['name' => 'Ragunan', 'postal_code' => '12550', 'latitude' => -6.3080, 'longitude' => 106.8250],
                                    ['name' => 'Kebagusan', 'postal_code' => '12520', 'latitude' => -6.3040, 'longitude' => 106.8370],
                                ],
                            ],
                            [
                                'name' => 'Mampang Prapatan',
                                'latitude' => -6.2514,
                                'longitude' => 106.8239,
                                'villages' => [
                                    ['name' => 'Bangka', 'postal_code' => '12730', 'latitude' => -6.2580, 'longitude' => 106.8180],
                                    ['name' => 'Pela Mampang', 'postal_code' => '12720', 'latitude' => -6.2520, 'longitude' => 106.8250],
                                    ['name' => 'Kuningan Barat', 'postal_code' => '12710', 'latitude' => -6.2410, 'longitude' => 106.8280],
                                ],
                            ],
                            [
                                'name' => 'Pancoran',
                                'latitude' => -6.2561,
                                'longitude' => 106.8451,
                                'villages' => [
                                    ['name' => 'Kalibata', 'postal_code' => '12740', 'latitude' => -6.2610, 'longitude' => 106.8520],
                                    ['name' => 'Duren Tiga', 'postal_code' => '12760', 'latitude' => -6.2520, 'longitude' => 106.8410],
                                    ['name' => 'Pancoran', 'postal_code' => '12780', 'latitude' => -6.2480, 'longitude' => 106.8480],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Jakarta Pusat',
                        'type' => 'Kota',
                        'latitude' => -6.1805,
                        'longitude' => 106.8284,
                        'districts' => [
                            [
                                'name' => 'Menteng',
                                'latitude' => -6.1963,
                                'longitude' => 106.8335,
                                'villages' => [
                                    ['name' => 'Menteng', 'postal_code' => '10310', 'latitude' => -6.1980, 'longitude' => 106.8310],
                                    ['name' => 'Gondangdia', 'postal_code' => '10350', 'latitude' => -6.1890, 'longitude' => 106.8340],
                                    ['name' => 'Cikini', 'postal_code' => '10330', 'latitude' => -6.1920, 'longitude' => 106.8420],
                                ],
                            ],
                            [
                                'name' => 'Tanah Abang',
                                'latitude' => -6.2001,
                                'longitude' => 106.8130,
                                'villages' => [
                                    ['name' => 'Bendungan Hilir', 'postal_code' => '10210', 'latitude' => -6.2150, 'longitude' => 106.8120],
                                    ['name' => 'Karet Tengsin', 'postal_code' => '10220', 'latitude' => -6.2080, 'longitude' => 106.8180],
                                    ['name' => 'Petamburan', 'postal_code' => '10260', 'latitude' => -6.1980, 'longitude' => 106.8040],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Jakarta Timur',
                        'type' => 'Kota',
                        'latitude' => -6.2250,
                        'longitude' => 106.9004,
                        'districts' => [
                            [
                                'name' => 'Jatinegara',
                                'latitude' => -6.2312,
                                'longitude' => 106.8710,
                                'villages' => [
                                    ['name' => 'Kampung Melayu', 'postal_code' => '13320', 'latitude' => -6.2270, 'longitude' => 106.8650],
                                    ['name' => 'Bidara Cina', 'postal_code' => '13330', 'latitude' => -6.2360, 'longitude' => 106.8680],
                                    ['name' => 'Cipinang Cempedak', 'postal_code' => '13340', 'latitude' => -6.2420, 'longitude' => 106.8750],
                                ],
                            ],
                            [
                                'name' => 'Duren Sawit',
                                'latitude' => -6.2345,
                                'longitude' => 106.9150,
                                'villages' => [
                                    ['name' => 'Duren Sawit', 'postal_code' => '13440', 'latitude' => -6.2350, 'longitude' => 106.9120],
                                    ['name' => 'Klender', 'postal_code' => '13470', 'latitude' => -6.2210, 'longitude' => 106.8990],
                                    ['name' => 'Pondok Kelapa', 'postal_code' => '13450', 'latitude' => -6.2480, 'longitude' => 106.9350],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Jakarta Barat',
                        'type' => 'Kota',
                        'latitude' => -6.1683,
                        'longitude' => 106.7588,
                        'districts' => [
                            [
                                'name' => 'Kebon Jeruk',
                                'latitude' => -6.1912,
                                'longitude' => 106.7680,
                                'villages' => [
                                    ['name' => 'Kebon Jeruk', 'postal_code' => '11530', 'latitude' => -6.1920, 'longitude' => 106.7670],
                                    ['name' => 'Kedoya Selatan', 'postal_code' => '11520', 'latitude' => -6.1820, 'longitude' => 106.7610],
                                    ['name' => 'Sukabumi Utara', 'postal_code' => '11540', 'latitude' => -6.2040, 'longitude' => 106.7790],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '32',
                'name' => 'Jawa Barat',
                'cities' => [
                    [
                        'name' => 'Bandung',
                        'type' => 'Kota',
                        'latitude' => -6.9175,
                        'longitude' => 107.6191,
                        'districts' => [
                            [
                                'name' => 'Coblong',
                                'latitude' => -6.8830,
                                'longitude' => 107.6160,
                                'villages' => [
                                    ['name' => 'Dago', 'postal_code' => '40135', 'latitude' => -6.8780, 'longitude' => 107.6180],
                                    ['name' => 'Lebak Siliwangi', 'postal_code' => '40132', 'latitude' => -6.8850, 'longitude' => 107.6100],
                                    ['name' => 'Cipaganti', 'postal_code' => '40131', 'latitude' => -6.8920, 'longitude' => 107.6040],
                                ],
                            ],
                            [
                                'name' => 'Sukajadi',
                                'latitude' => -6.8850,
                                'longitude' => 107.5890,
                                'villages' => [
                                    ['name' => 'Pasteur', 'postal_code' => '40161', 'latitude' => -6.8920, 'longitude' => 107.5950],
                                    ['name' => 'Sukawarna', 'postal_code' => '40164', 'latitude' => -6.8810, 'longitude' => 107.5820],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Depok',
                        'type' => 'Kota',
                        'latitude' => -6.4025,
                        'longitude' => 106.7942,
                        'districts' => [
                            [
                                'name' => 'Beji',
                                'latitude' => -6.3680,
                                'longitude' => 106.8220,
                                'villages' => [
                                    ['name' => 'Pondok Cina', 'postal_code' => '16424', 'latitude' => -6.3650, 'longitude' => 106.8320],
                                    ['name' => 'Kukusan', 'postal_code' => '16425', 'latitude' => -6.3620, 'longitude' => 106.8170],
                                    ['name' => 'Kemiri Muka', 'postal_code' => '16423', 'latitude' => -6.3770, 'longitude' => 106.8270],
                                ],
                            ],
                            [
                                'name' => 'Pancoran Mas',
                                'latitude' => -6.3980,
                                'longitude' => 106.8090,
                                'villages' => [
                                    ['name' => 'Depok', 'postal_code' => '16431', 'latitude' => -6.3990, 'longitude' => 106.8210],
                                    ['name' => 'Depok Jaya', 'postal_code' => '16432', 'latitude' => -6.3930, 'longitude' => 106.8120],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Bekasi',
                        'type' => 'Kota',
                        'latitude' => -6.2383,
                        'longitude' => 106.9756,
                        'districts' => [
                            [
                                'name' => 'Bekasi Barat',
                                'latitude' => -6.2360,
                                'longitude' => 106.9780,
                                'villages' => [
                                    ['name' => 'Kranji', 'postal_code' => '17135', 'latitude' => -6.2310, 'longitude' => 106.9720],
                                    ['name' => 'Kota Baru', 'postal_code' => '17133', 'latitude' => -6.2230, 'longitude' => 106.9650],
                                ],
                            ],
                            [
                                'name' => 'Bekasi Selatan',
                                'latitude' => -6.2620,
                                'longitude' => 106.9850,
                                'villages' => [
                                    ['name' => 'Pekayon Jaya', 'postal_code' => '17148', 'latitude' => -6.2680, 'longitude' => 106.9890],
                                    ['name' => 'Jaka Setia', 'postal_code' => '17147', 'latitude' => -6.2750, 'longitude' => 106.9810],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Bogor',
                        'type' => 'Kota',
                        'latitude' => -6.5971,
                        'longitude' => 106.8060,
                        'districts' => [
                            [
                                'name' => 'Bogor Tengah',
                                'latitude' => -6.5980,
                                'longitude' => 106.7990,
                                'villages' => [
                                    ['name' => 'Babakan', 'postal_code' => '16128', 'latitude' => -6.5920, 'longitude' => 106.8040],
                                    ['name' => 'Paledang', 'postal_code' => '16122', 'latitude' => -6.6020, 'longitude' => 106.7920],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '36',
                'name' => 'Banten',
                'cities' => [
                    [
                        'name' => 'Tangerang Selatan',
                        'type' => 'Kota',
                        'latitude' => -6.2888,
                        'longitude' => 106.7179,
                        'districts' => [
                            [
                                'name' => 'Serpong',
                                'latitude' => -6.3150,
                                'longitude' => 106.6690,
                                'villages' => [
                                    ['name' => 'Lengkong Gudang', 'postal_code' => '15321', 'latitude' => -6.3080, 'longitude' => 106.6780],
                                    ['name' => 'Rawa Buntu', 'postal_code' => '15318', 'latitude' => -6.3210, 'longitude' => 106.6850],
                                    ['name' => 'Ciater', 'postal_code' => '15310', 'latitude' => -6.3310, 'longitude' => 106.6980],
                                ],
                            ],
                            [
                                'name' => 'Ciputat',
                                'latitude' => -6.3110,
                                'longitude' => 106.7460,
                                'villages' => [
                                    ['name' => 'Ciputat', 'postal_code' => '15411', 'latitude' => -6.3130, 'longitude' => 106.7480],
                                    ['name' => 'Cipayung', 'postal_code' => '15411', 'latitude' => -6.3220, 'longitude' => 106.7510],
                                ],
                            ],
                            [
                                'name' => 'Pondok Aren',
                                'latitude' => -6.2690,
                                'longitude' => 106.7120,
                                'villages' => [
                                    ['name' => 'Bintaro', 'postal_code' => '15225', 'latitude' => -6.2620, 'longitude' => 106.7210],
                                    ['name' => 'Jurang Mangu Timur', 'postal_code' => '15222', 'latitude' => -6.2730, 'longitude' => 106.7320],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Tangerang',
                        'type' => 'Kota',
                        'latitude' => -6.1783,
                        'longitude' => 106.6319,
                        'districts' => [
                            [
                                'name' => 'Tangerang',
                                'latitude' => -6.1810,
                                'longitude' => 106.6340,
                                'villages' => [
                                    ['name' => 'Sukasari', 'postal_code' => '15118', 'latitude' => -6.1820, 'longitude' => 106.6360],
                                    ['name' => 'Babakan', 'postal_code' => '15118', 'latitude' => -6.1870, 'longitude' => 106.6420],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '35',
                'name' => 'Jawa Timur',
                'cities' => [
                    [
                        'name' => 'Surabaya',
                        'type' => 'Kota',
                        'latitude' => -7.2575,
                        'longitude' => 112.7521,
                        'districts' => [
                            [
                                'name' => 'Gubeng',
                                'latitude' => -7.2720,
                                'longitude' => 112.7580,
                                'villages' => [
                                    ['name' => 'Airlangga', 'postal_code' => '60286', 'latitude' => -7.2690, 'longitude' => 112.7610],
                                    ['name' => 'Kertajaya', 'postal_code' => '60282', 'latitude' => -7.2780, 'longitude' => 112.7540],
                                    ['name' => 'Gubeng', 'postal_code' => '60281', 'latitude' => -7.2650, 'longitude' => 112.7530],
                                ],
                            ],
                            [
                                'name' => 'Wonokromo',
                                'latitude' => -7.2980,
                                'longitude' => 112.7380,
                                'villages' => [
                                    ['name' => 'Darmo', 'postal_code' => '60241', 'latitude' => -7.2910, 'longitude' => 112.7350],
                                    ['name' => 'Sawunggaling', 'postal_code' => '60242', 'latitude' => -7.2990, 'longitude' => 112.7290],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Malang',
                        'type' => 'Kota',
                        'latitude' => -7.9666,
                        'longitude' => 112.6326,
                        'districts' => [
                            [
                                'name' => 'Klojen',
                                'latitude' => -7.9780,
                                'longitude' => 112.6310,
                                'villages' => [
                                    ['name' => 'Oro-Oro Dowo', 'postal_code' => '65119', 'latitude' => -7.9710, 'longitude' => 112.6270],
                                    ['name' => 'Kauman', 'postal_code' => '65119', 'latitude' => -7.9820, 'longitude' => 112.6290],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '34',
                'name' => 'DI Yogyakarta',
                'cities' => [
                    [
                        'name' => 'Yogyakarta',
                        'type' => 'Kota',
                        'latitude' => -7.7956,
                        'longitude' => 110.3695,
                        'districts' => [
                            [
                                'name' => 'Gondokusuman',
                                'latitude' => -7.7830,
                                'longitude' => 110.3780,
                                'villages' => [
                                    ['name' => 'Terban', 'postal_code' => '55223', 'latitude' => -7.7780, 'longitude' => 110.3740],
                                    ['name' => 'Kotabaru', 'postal_code' => '55224', 'latitude' => -7.7850, 'longitude' => 110.3720],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '33',
                'name' => 'Jawa Tengah',
                'cities' => [
                    [
                        'name' => 'Semarang',
                        'type' => 'Kota',
                        'latitude' => -6.9932,
                        'longitude' => 110.4203,
                        'districts' => [
                            [
                                'name' => 'Semarang Tengah',
                                'latitude' => -6.9830,
                                'longitude' => 110.4190,
                                'villages' => [
                                    ['name' => 'Sekayu', 'postal_code' => '50132', 'latitude' => -6.9810, 'longitude' => 110.4140],
                                    ['name' => 'Pandansari', 'postal_code' => '50139', 'latitude' => -6.9740, 'longitude' => 110.4220],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '51',
                'name' => 'Bali',
                'cities' => [
                    [
                        'name' => 'Denpasar',
                        'type' => 'Kota',
                        'latitude' => -8.6705,
                        'longitude' => 115.2126,
                        'districts' => [
                            [
                                'name' => 'Denpasar Selatan',
                                'latitude' => -8.6940,
                                'longitude' => 115.2280,
                                'villages' => [
                                    ['name' => 'Sanur', 'postal_code' => '80228', 'latitude' => -8.6880, 'longitude' => 115.2620],
                                    ['name' => 'Renon', 'postal_code' => '80226', 'latitude' => -8.6790, 'longitude' => 115.2340],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '12',
                'name' => 'Sumatera Utara',
                'cities' => [
                    [
                        'name' => 'Medan',
                        'type' => 'Kota',
                        'latitude' => 3.5952,
                        'longitude' => 98.6722,
                        'districts' => [
                            [
                                'name' => 'Medan Baru',
                                'latitude' => 3.5780,
                                'longitude' => 98.6590,
                                'villages' => [
                                    ['name' => 'Petisah Hulu', 'postal_code' => '20152', 'latitude' => 3.5840, 'longitude' => 98.6650],
                                    ['name' => 'Babura', 'postal_code' => '20154', 'latitude' => 3.5720, 'longitude' => 98.6540],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '13',
                'name' => 'Sumatera Barat',
                'cities' => [
                    [
                        'name' => 'Padang',
                        'type' => 'Kota',
                        'latitude' => -0.9471,
                        'longitude' => 100.4172,
                        'districts' => [
                            [
                                'name' => 'Padang Barat',
                                'latitude' => -0.9520,
                                'longitude' => 100.3540,
                                'villages' => [
                                    ['name' => 'Ujung Gurun', 'postal_code' => '25114', 'latitude' => -0.9480, 'longitude' => 100.3580],
                                    ['name' => 'Purus', 'postal_code' => '25115', 'latitude' => -0.9420, 'longitude' => 100.3510],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '14',
                'name' => 'Riau',
                'cities' => [
                    [
                        'name' => 'Pekanbaru',
                        'type' => 'Kota',
                        'latitude' => 0.5071,
                        'longitude' => 101.4478,
                        'districts' => [
                            [
                                'name' => 'Tampan',
                                'latitude' => 0.4720,
                                'longitude' => 101.3850,
                                'villages' => [
                                    ['name' => 'Simpang Baru', 'postal_code' => '28293', 'latitude' => 0.4680, 'longitude' => 101.3810],
                                    ['name' => 'Tuah Karya', 'postal_code' => '28291', 'latitude' => 0.4780, 'longitude' => 101.3920],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '16',
                'name' => 'Sumatera Selatan',
                'cities' => [
                    [
                        'name' => 'Palembang',
                        'type' => 'Kota',
                        'latitude' => -2.9761,
                        'longitude' => 104.7754,
                        'districts' => [
                            [
                                'name' => 'Ilir Barat I',
                                'latitude' => -2.9890,
                                'longitude' => 104.7380,
                                'villages' => [
                                    ['name' => 'Bukit Lama', 'postal_code' => '30139', 'latitude' => -2.9920, 'longitude' => 104.7310],
                                    ['name' => 'Demang Lebar Daun', 'postal_code' => '30137', 'latitude' => -2.9810, 'longitude' => 104.7420],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '18',
                'name' => 'Lampung',
                'cities' => [
                    [
                        'name' => 'Bandar Lampung',
                        'type' => 'Kota',
                        'latitude' => -5.4292,
                        'longitude' => 105.2611,
                        'districts' => [
                            [
                                'name' => 'Kedaton',
                                'latitude' => -5.3850,
                                'longitude' => 105.2580,
                                'villages' => [
                                    ['name' => 'Kedaton', 'postal_code' => '35141', 'latitude' => -5.3820, 'longitude' => 105.2550],
                                    ['name' => 'Sukamenanti', 'postal_code' => '35146', 'latitude' => -5.3890, 'longitude' => 105.2630],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '64',
                'name' => 'Kalimantan Timur',
                'cities' => [
                    [
                        'name' => 'Samarinda',
                        'type' => 'Kota',
                        'latitude' => -0.5021,
                        'longitude' => 117.1537,
                        'districts' => [
                            [
                                'name' => 'Samarinda Kota',
                                'latitude' => -0.5010,
                                'longitude' => 117.1510,
                                'villages' => [
                                    ['name' => 'Bugis', 'postal_code' => '75111', 'latitude' => -0.4990, 'longitude' => 117.1480],
                                    ['name' => 'Pasar Pagi', 'postal_code' => '75111', 'latitude' => -0.5030, 'longitude' => 117.1540],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Balikpapan',
                        'type' => 'Kota',
                        'latitude' => -1.2379,
                        'longitude' => 116.8529,
                        'districts' => [
                            [
                                'name' => 'Balikpapan Kota',
                                'latitude' => -1.2620,
                                'longitude' => 116.8310,
                                'villages' => [
                                    ['name' => 'Klandasan Ilir', 'postal_code' => '76113', 'latitude' => -1.2650, 'longitude' => 116.8350],
                                    ['name' => 'Damai', 'postal_code' => '76114', 'latitude' => -1.2550, 'longitude' => 116.8420],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '61',
                'name' => 'Kalimantan Barat',
                'cities' => [
                    [
                        'name' => 'Pontianak',
                        'type' => 'Kota',
                        'latitude' => -0.0263,
                        'longitude' => 109.3425,
                        'districts' => [
                            [
                                'name' => 'Pontianak Selatan',
                                'latitude' => -0.0410,
                                'longitude' => 109.3490,
                                'villages' => [
                                    ['name' => 'Akcaya', 'postal_code' => '78121', 'latitude' => -0.0450, 'longitude' => 109.3420],
                                    ['name' => 'Benua Melayu Laut', 'postal_code' => '78123', 'latitude' => -0.0380, 'longitude' => 109.3550],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '63',
                'name' => 'Kalimantan Selatan',
                'cities' => [
                    [
                        'name' => 'Banjarmasin',
                        'type' => 'Kota',
                        'latitude' => -3.3194,
                        'longitude' => 114.5908,
                        'districts' => [
                            [
                                'name' => 'Banjarmasin Tengah',
                                'latitude' => -3.3210,
                                'longitude' => 114.5920,
                                'villages' => [
                                    ['name' => 'Kertak Baru Ilir', 'postal_code' => '70111', 'latitude' => -3.3240, 'longitude' => 114.5880],
                                    ['name' => 'Antasan Besar', 'postal_code' => '70114', 'latitude' => -3.3160, 'longitude' => 114.5940],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '71',
                'name' => 'Sulawesi Utara',
                'cities' => [
                    [
                        'name' => 'Manado',
                        'type' => 'Kota',
                        'latitude' => 1.4748,
                        'longitude' => 124.8421,
                        'districts' => [
                            [
                                'name' => 'Wenang',
                                'latitude' => 1.4820,
                                'longitude' => 124.8440,
                                'villages' => [
                                    ['name' => 'Tikala Kumaraka', 'postal_code' => '95124', 'latitude' => 1.4850, 'longitude' => 124.8480],
                                    ['name' => 'Bumi Beringin', 'postal_code' => '95113', 'latitude' => 1.4780, 'longitude' => 124.8390],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '52',
                'name' => 'Nusa Tenggara Barat',
                'cities' => [
                    [
                        'name' => 'Mataram',
                        'type' => 'Kota',
                        'latitude' => -8.5833,
                        'longitude' => 116.1167,
                        'districts' => [
                            [
                                'name' => 'Mataram',
                                'latitude' => -8.5860,
                                'longitude' => 116.1120,
                                'villages' => [
                                    ['name' => 'Pagesangan', 'postal_code' => '83127', 'latitude' => -8.5910, 'longitude' => 116.1080],
                                    ['name' => 'Mataram Timur', 'postal_code' => '83121', 'latitude' => -8.5820, 'longitude' => 116.1190],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '81',
                'name' => 'Maluku',
                'cities' => [
                    [
                        'name' => 'Ambon',
                        'type' => 'Kota',
                        'latitude' => -3.6554,
                        'longitude' => 128.1908,
                        'districts' => [
                            [
                                'name' => 'Sirimau',
                                'latitude' => -3.6890,
                                'longitude' => 128.1950,
                                'villages' => [
                                    ['name' => 'Batu Meja', 'postal_code' => '97124', 'latitude' => -3.6920, 'longitude' => 128.1980],
                                    ['name' => 'Honipopu', 'postal_code' => '97126', 'latitude' => -3.6850, 'longitude' => 128.1880],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'code' => '91',
                'name' => 'Papua',
                'cities' => [
                    [
                        'name' => 'Jayapura',
                        'type' => 'Kota',
                        'latitude' => -2.5916,
                        'longitude' => 140.6690,
                        'districts' => [
                            [
                                'name' => 'Jayapura Utara',
                                'latitude' => -2.5350,
                                'longitude' => 140.7050,
                                'villages' => [
                                    ['name' => 'Gurabesi', 'postal_code' => '99111', 'latitude' => -2.5380, 'longitude' => 140.7080],
                                    ['name' => 'Tanjung Ria', 'postal_code' => '99117', 'latitude' => -2.5290, 'longitude' => 140.7120],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($regions as $provData) {
            $province = Province::firstOrCreate(
                ['code' => $provData['code']],
                ['name' => $provData['name']]
            );

            foreach ($provData['cities'] as $cityData) {
                $city = City::firstOrCreate(
                    [
                        'province_id' => $province->id,
                        'name' => $cityData['name'],
                    ],
                    [
                        'type' => $cityData['type'],
                        'latitude' => $cityData['latitude'],
                        'longitude' => $cityData['longitude'],
                    ]
                );

                foreach ($cityData['districts'] as $distData) {
                    $district = District::firstOrCreate(
                        [
                            'city_id' => $city->id,
                            'name' => $distData['name'],
                        ],
                        [
                            'latitude' => $distData['latitude'],
                            'longitude' => $distData['longitude'],
                        ]
                    );

                    foreach ($distData['villages'] as $villData) {
                        Village::firstOrCreate(
                            [
                                'district_id' => $district->id,
                                'name' => $villData['name'],
                            ],
                            [
                                'postal_code' => $villData['postal_code'],
                                'latitude' => $villData['latitude'],
                                'longitude' => $villData['longitude'],
                            ]
                        );
                    }
                }
            }
        }
    }
}
