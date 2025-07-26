<?php
header('location: /0/');

$name = $_REQUEST['n'] ?? '';
$site = $_REQUEST['a'] ?? '';
$date = $_REQUEST['d'] ?? '';

date_default_timezone_set('Asia/Manila');
$currentDateTime = date('Y-m-d h:i:s A');

// $isPrintMode = $_SESSION['isPrintMode'] ?? false;

// if ($isPrintMode) {
//     $print = 'Print record - recorder_' . base64_decode($name) . '_site_' . base64_decode($site) . '_' . $date . '_printDate-' . $currentDateTime;
// } else {
//     $print = 'PEFCS-DB';
// }

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// if (empty($_SESSION['userName'])) {
//     header('location: /login');
//     exit();
// }

require $_SERVER['DOCUMENT_ROOT'] . '/routes/routes.php';
route('authentication');
route('statistics');
route('photos');
route('dataApproval');

use app\models\dataApproval;
use app\models\statistics as calculate;


$apiUrl = 'https://www.nursery.pefcarbonsink.info/API/v1/seedlings/statistics';
$seedling = calculate::fetch($apiUrl);

// $nursery = [];

// foreach ($seedling['overview']['monthYears'] as $row) {
//     $nursery[$row['month']] = $row['count'];
// }

// $nurserySiteCount = [];

// foreach ($seedling['overview']['siteCount'] as $row) {
//     $nurserySiteCount[$row['nursery_site']] = $row['count'];
// }

$statistics = calculate::statistics();
$data_json = json_encode($statistics['health']);
$sitesDataJSON = json_encode($statistics['sites']);

$n_tagging = 'https://www.nursery.pefcarbonsink.info/API/tagging/';
$tagging = calculate::fetch($n_tagging);

$sClass = calculate::nurseryClass();
$sNursery = calculate::nurseryStatistics();

$nav = $_REQUEST['nav'] ?? '';

$path = match ($nav) {
    base64_encode('data') => 'page/data/index.php',
    base64_encode('trees') => 'page/map/index.php',
    base64_encode('dataValidation') => 'page/data/dataValidation.php',
    base64_encode('request_Manager') => 'page/data/request_manager.php',
    base64_encode('n_data') => 'n_page/data/index.php',
    base64_encode('n_trees') => 'n_page/map/index.php',
    base64_encode('n_dataValidation') => 'n_page/data/dataValidation.php',
    default => false
};



$treeStat = calculate::treeStat('');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PEFCS-DB</title>
    <link rel="icon" href="../app/assets/PEF_LOGO.png" type="png">
    <link rel="stylesheet" href="/css/main.min.css">
    <link rel="stylesheet" href="/css/footer.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css'>
    <link rel="stylesheet" href="/css/loader.min.css">
    <script src="/js/loader.min.js"></script>
    <script src="/js/dropDown.min.js"></script>
    <script src="/js/confirmButton.min.js"></script>
    <script src="/js/scroll.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    .p-ranking thead {
        position: sticky;
        top: 0;
    }

    .container {
        padding: 0 10px;
        margin-bottom: 10px;
        height: 80dvh;
    }
    </style>
</head>

<body>
    <div class="head" id="head" style="position:fixed;">
        <div class="nav-container">
            <div class="dropdown">
                <button onclick="toggleDropdown('dropdown1')" class="mobile-menu">☰ </button>
                <div id="dropdown1" class="dropdown-content mobile">
                    <a href="/">Dashboard</a>
                    <a href="">Photos</a>
                    <a href="">Statics</a>
                    <a href="">QR codes</a>
                    <a href="">QR scanner</a>
                    <a href="">QR scanner</a>
                    <a href="/?nav=dHJlZXM=">Sites</a>
                    <a href="/login/logout">Sign out</a>
                </div>
            </div>
        </div>

        <div class="desktop-nav">
            <div class="dropdown">
                <button onclick="toggleDropdown('dropdown2')" class="dev">Developer's tools</button>
                <div id="dropdown2" class="dropdown-content d-dev">
                    <a class="btn2" href="https://kc.kobotoolbox.org/api/v1/data" target="_blank">KoBoCAT REST API</a>
                    <a class="btn2" href="https://developers.google.com/drive/api/guides/about-sdk"
                        target="_blank">Gdrive API</a>
                    <a class="btn2" href="https://developers.google.com/photos/library/guides/overview"
                        target="_blank">Gphotos API</a>
                    <a class="btn2" href="https://laravel.com/docs/10.x/releases" target="_blank">PHP laravel v.10</a>
                    <a class="btn2" href="https://dashboard.paymongo.com" target="_blank">PayMongo</a>
                    <a class="btn2" href="https://ifastnet.com/portal/clientarea.php?action=productdetails&id=318658"
                        target="_blank">Web hosting server</a>
                    <a class="btn2" href="/page/documentations/" target="_blank">Documentations</a>
                </div>
            </div>
            <div class="dropdown">
                <button onclick="toggleDropdown('dropdown3')" class="admin">Admin tools</button>
                <div id="dropdown3" class="dropdown-content d-admin">
                    <a class="btn2" href="/?nav=<?php echo base64_encode('request_Manager'); ?>">Request manager</a>
                    <a class="btn2" href="#">Activity manager</a>
                    <a class="btn2" href="https://www.tree.pefcarbonsink.info/tree-database/" target="_blank">Tree
                        Database</a>
                    <a class="btn2" href="/#reforestation">Reforestation Updates</a>
                    <a class="btn2" href="/#nursery">Nursery Updates</a>
                    <a class="btn2" href="https://www.qrscanner.pefcarbonsink.info/tree_qr_code/" target="_blank">Create
                        QR</a>
                    <a class="btn2" href="https://www.qrscanner.pefcarbonsink.info/qrcode_manager/" target="_blank">QR
                        Manager</a>
                    <a class="btn2" href="https://www.nursery.pefcarbonsink.info" target="_blank">Nursery Manager</a>
                    <a class="btn2" href="https://www.qrscanner.pefcarbonsink.info" target="_blank">QrScanner</a>
                </div>
            </div>
            <div class="dropdown">
                <button onclick="toggleDropdown('dropdown4')" class="stat">Statistics</button>
                <div id="dropdown4" class="dropdown-content d-stat">
                    <a href="">Advance data search</a>
                    <a href="">Advance data statistics</a>
                    <a href="">Tree suitability analysis</a>
                </div>
            </div>

            <button onclick="confirmLogout()" class="stat">Sign out</button>
        </div>

    </div>
    <button id="scrollBtn" onclick="scrollToTop()">
        <svg xmlns="http://www.w3.org/2000/svg" height="50" viewBox="0 -960 960 960" width="50">
            <path d="M480-528 296-344l-56-56 240-240 240 240-56 56-184-184Z" />
        </svg>
    </button>
    <div class="sidebar">
        <div class="sidebar-container"><button title="Dashboard" onclick="window.location='/';"><svg
                    xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="30">
                    <path
                        d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Zm80-400h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z"
                        stroke-width="1" />
                </svg>
            </button><button title="Data" onclick="window.location='/?nav=<?php echo base64_encode('data'); ?>'"><svg
                    xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="30">
                    <path
                        d="M146.666-160q-27 0-46.833-20.167Q80-200.333 80-226.666v-506.668q0-26.333 19.833-46.499Q119.666-800 146.666-800H414l66.667 66.666h332.667q26.333 0 46.499 20.167Q880-693.001 880-666.667v440.001q0 26.333-20.167 46.499Q839.667-160 813.334-160H146.666Zm0-66.666h666.668v-440.001H453l-66.666-66.667H146.666v506.668Zm0 0v-506.668 506.668Z" />
                </svg>
            </button><button title="Planting locations"
                onclick="window.location='/?nav=<?php echo base64_encode('trees'); ?>'"><svg
                    xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="30">
                    <path
                        d="M200-80v-80h240v-160h-80q-83 0-141.5-58.5T160-520q0-60 33-110.5t89-73.5q9-75 65.5-125.5T480-880q76 0 132.5 50.5T678-704q56 23 89 73.5T800-520q0 83-58.5 141.5T600-320h-80v160h240v80H200Zm160-320h240q50 0 85-35t35-85q0-36-20.5-66T646-630l-42-18-6-46q-6-45-39.5-75.5T480-800q-45 0-78.5 30.5T362-694l-6 46-42 18q-33 14-53.5 44T240-520q0 50 35 85t85 35Zm120-200Z" />
                </svg></button></div>
    </div>
    <section id="reforestation"></section>
    <div class="dashboad-container">
        <div class="tools-container" id="tools"><button><svg xmlns="http://www.w3.org/2000/svg" height="20"
                    viewBox="0 -960 960 960" width="20">
                    <path
                        d="M696.091-116q-41.63 0-70.86-29.167Q596-174.333 596-216q0-8.979 1.385-16.528 1.384-7.549 4.153-17.087L335.846-413.077q-12.692 17.539-31.508 25.308Q285.523-380 264-380q-41.667 0-70.833-29.14Q164-438.28 164-479.91q0-41.629 29.167-70.859Q222.333-580 264-580q21.231 0 40.192 8.269 18.962 8.269 31.654 24.808l265.692-163.462q-2.769-9.538-4.153-17.087Q596-735.021 596-744q0-41.667 29.14-70.833Q654.281-844 695.909-844q41.63 0 70.86 29.14Q796-785.719 796-744.091q0 41.63-29.167 70.86Q737.667-644 696-644q-21.523 0-40.339-7.769-18.815-7.769-31.507-25.308L358.462-513.615q2.769 9.538 4.154 17.033Q364-489.088 364-480.159q0 8.928-1.384 16.582-1.385 7.654-4.154 17.192l265.692 163.462q12.692-18.539 31.507-25.808Q674.477-316 696-316q41.667 0 70.833 29.14Q796-257.72 796-216.09q0 41.629-29.14 70.859Q737.719-116 696.091-116ZM696-676q28.092 0 48.046-19.954T764-744q0-28.092-19.954-48.046T696-812q-28.092 0-48.046 19.954T628-744q0 28.092 19.954 48.046T696-676ZM264-412q28.092 0 48.046-19.954T332-480q0-28.092-19.954-48.046T264-548q-28.092 0-48.046 19.954T196-480q0 28.092 19.954 48.046T264-412Zm432 264q28.092 0 48.046-19.954T764-216q0-28.092-19.954-48.046T696-284q-28.092 0-48.046 19.954T628-216q0 28.092 19.954 48.046T696-148Zm0-596ZM264-480Zm432 264Z" />
                </svg>Share</button><button onclick="window.print();"><svg xmlns="http://www.w3.org/2000/svg"
                    height="20" viewBox="0 -960 960 960" width="20">
                    <path
                        d="M648-599.385v-120H312v120h-32v-152h400v152h-32Zm-456.923 32h578.846-578.846Zm501.328 96q15.21 0 25.71-10.289t10.5-25.5q0-15.211-10.289-25.711-10.289-10.5-25.5-10.5t-25.711 10.29q-10.5 10.289-10.5 25.5 0 15.21 10.29 25.71 10.289 10.5 25.5 10.5ZM648-216v-165.538H312V-216h336Zm32 32H280v-144H159.077v-271.385h641.846V-328H680v144Zm89.923-176v-207.385H191.077V-360H280v-53.539h400V-360h89.923Z" />
                </svg>Print</button><button><svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960"
                    width="20">
                    <path
                        d="M240-232q-63.846 0-107.923-45.731Q88-323.461 88-387.308q0-69.769 52-118.077 52-48.307 120.846-35.231 13.077-79.307 75.115-131.615Q398-724.539 464-724.539q12.777 0 22.389 7.962Q496-708.615 496-694.538v295.23l75.462-77L594.769-453 480-338.231 365.231-453l23.307-23.308 75.462 77v-295.23q-70.231 6.23-123.115 61.923Q288-576.923 283-504h-43q-49.714 0-84.857 35.202t-35.143 85Q120-334 155.143-299T240-264h504q40.32 0 68.16-27.775 27.84-27.774 27.84-68Q840-400 812.16-428q-27.84-28-68.16-28h-72v-72q0-36.538-17-69.538T606-656v-37.616q45.308 26.154 71.654 70.336Q704-579.099 704-528v40h24.615q58.462-6.154 100.924 31.692Q872-418.462 872-361q0 54.846-37.577 91.923Q796.846-232 744-232H240Zm240-267.769Z" />
                </svg>Export</button></div>
        <div class="reforestation">
            <h3>Reforestation overview</h3>
            <div class="refo-summary">
                <div class="refo-info"><span>Total Planted trees</span>
                    <h2><?php echo number_format($treeStat[0]); ?></h2>
                </div>
                <div class="refo-info"><span>Total recorded sites</span>
                    <h2><?php echo $treeStat[6]; ?></h2>
                </div>
                <div class="refo-info"><span>Avg. tree DBH</span>
                    <h2><?php echo number_format($treeStat[1], 2); ?><code>cm</code></h2>
                </div>
                <div class="refo-info"><span>Avg. total tree height</span>
                    <h2><?php echo number_format($treeStat[2], 2); ?><code>m</code></h2>
                </div>
                <div class="refo-info"><span>Avg. biomass</span>
                    <h2><?php echo number_format($treeStat[3], 2); ?><code>kg</code></h2>
                </div>
                <div class="refo-info"><span>Avg. carbon stored</span>
                    <h2><?php echo number_format($treeStat[4], 2); ?><code>kg</code></h2>
                </div>
                <div class="refo-info"><span>Avg. CO<sup>2</sup>stored</span>
                    <h2><?php echo number_format($treeStat[5], 2); ?><code>kg</code></h2>
                </div>
            </div>
        </div>
        <hr>
        <div class="p-month">
            <canvas id="barChart" width="400" height="200"><canvas>
        </div>
        <div class="p-sites">
            <h5>Planted tree per site</h5><canvas id="doughnutChart"></canvas>
        </div>
        <div class="user-monitoring">
            <div class="p-ranking">
                <h5 style="text-align:center;">Recorders Entry data log</h5>
                <div class="D-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Site</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($statistics['recorder'] as $recorder): ?>
                                <?php $link = '/?nav=' . base64_encode('dataValidation') . '&n=' . base64_encode($recorder['name']) . '&a=' . base64_encode($recorder['site']) . '&rcred=true&d=' . $recorder['dateWord']; ?>
                                <tr onclick="window.location='<?php echo $link ?>'">
                                    <td><?php echo $recorder['name']; ?></td>
                                    <td><?php echo $recorder['site']; ?></td>
                                    <td><?php echo number_format($recorder['data_entry_count']); ?></td>
                                    <td><?php echo $recorder['date']; ?></td>
                                </tr><?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-ranking">
                <h5 style="text-align:center;">Tree health</h5>
                <div class="D-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Area</th>
                                <th>Status</th>
                                <th>Count</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($statistics['tree_health_per_site'] as $site): ?>
                                <?php $link = '/?nav=' . base64_encode('dataValidation') . '&s=' . base64_encode($site['health']) . '&a=' . base64_encode($site['area']) . '&d=' . $site['dateWord']; ?>
                                <tr onclick="window.location='<?php echo $link; ?>'">
                                    <td><?php echo $site['area']; ?></td>
                                    <td><?php echo $site['health']; ?></td>
                                    <td><?php echo number_format($site['health_count']); ?></td>
                                    <td><?php echo $site['date']; ?></td>
                                </tr><?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-ranking">
                <h5 style="text-align:center;">Data validation (for approval)</h5>
                <div class="D-container">
                    <table>
                        <thead>
                            <tr>
                                <th>name</th>
                                <th>Site</th>
                                <th>Count</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($statistics['approval'] as $site): ?>
                                <?php $link = '/?nav=' . base64_encode('dataValidation') . '&n=' . base64_encode($site['name']) . '&a=' . base64_encode($site['site']) . '&d=' . $site['dateWord']; ?>
                                <tr onclick="window.location='<?php echo $link; ?>'">
                                    <td><?php echo $site['name']; ?></td>
                                    <td><?php echo $site['site']; ?></td>
                                    <td><?php echo number_format($site['count']); ?></td>
                                    <td style="text-align:center;"><?php echo $site['date']; ?></td>
                                </tr><?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr id="nursery" />
        <div class="nursery">
            <h3>Nursery overview</h3>
            <div class="nur-summary">
                <div class="nur-info"><span>Total Seedlings</span>
                    <h2><?php echo number_format($sNursery['count']); ?></h2>
                </div>
                <div class="nur-info"><span>PM: Wildlings</span>
                    <h2><?php echo number_format($sNursery['wildlings']); ?></h2>
                </div>
                <div class="nur-info"><span>PM: Cuttings</span>
                    <h2><?php echo number_format($sNursery['cuttings']); ?></h2>
                </div>
                <div class="nur-info"><span>PM: Seeds</span>
                    <h2><?php echo number_format($sNursery['seeds']); ?></h2>
                </div>
                <div class="nur-info"><span>Species count</span>
                    <h2><?php echo number_format($sNursery['species']); ?></h2>
                </div>
                <div class="nur-info"><span>Available for planting</span>
                    <h2><?php echo number_format($sNursery['planting']); ?></h2>
                </div>
                <div class="nur-info"><span>Released</span>
                    <h2><?php echo number_format($sNursery['released']); ?></h2>
                </div>
            </div>
            <hr>
            <div class="n-month">
                <canvas id="barChart2" width="400" height="200"></canvas>
            </div>
            <div class="n-sites">
                <h5>Propagation per nursery site</h5>
                <canvas id="doughnutChart2"></canvas>
            </div>
            <div class="user-monitoring">
                <div class="p-ranking">
                    <h5 style="text-align:center;">User Status</h5>
                    <div class="today-activity"></div>
                </div>
                <div class="p-ranking">
                    <h5 style="text-align:center;">Seedling class</h5>
                    <div class="D-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>name</th>
                                    <th>Class</th>
                                    <th>Count</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sClass as $tag): ?>
                                    <?php $link = "/?nav=" . base64_encode('n_dataValidation') . "&nr=" . base64_encode($tag['recorder']) . "&na=" . base64_encode($tag['n_area']) . "&nd=" . base64_encode($tag['date_only']) . '&c=' . 1; ?>
                                    <tr onclick="window.location='<?php echo $link; ?>'">
                                        <td><?php echo $tag['recorder']; ?></td>
                                        <td><?php echo $tag['status']; ?></td>
                                        <td><?php echo $tag['count']; ?></td>
                                        <td><?php echo $tag['date_only']; ?></td>
                                    </tr><?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="p-ranking">
                    <h5 style="text-align:center;">Data validation (for approval)</h5>
                    <div class="D-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>name</th>
                                    <th>Site</th>
                                    <th>Count</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tagging as $tag): ?>
                                    <?php $link = "/?nav=" . base64_encode('n_dataValidation') . "&nr=" . base64_encode($tag['recorder']) . "&na=" . base64_encode($tag['n_area']) . "&nd=" . base64_encode($tag['date_only']) . '&c=' . 1; ?>
                                    <tr onclick="window.location='<?php echo $link; ?>'">
                                        <td><?php echo $tag['recorder']; ?></td>
                                        <td><?php echo $tag['n_area']; ?></td>
                                        <td><?php echo $tag['count']; ?></td>
                                        <td title="<?php echo $tag['date']; ?>"><?php echo $tag['date_only']; ?></td>
                                    </tr><?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <div class="col-1">
                <p>&copy 2023 - Phippine Eagle Foundation Inc.</p>
            </div>
            <div class="col-2"></div>
            <div class="col-3">
                <div class="wrapper">
                    <a href="https://www.facebook.com/phileaglefdn" class="icon facebook">
                        <div class="tooltip">Facebook</div>
                        <span><i class="fab fa-facebook-f"></i></span>
                    </a>
                    <a href="#" class="icon twitter">
                        <div class="tooltip">Twitter</div>
                        <span><i class="fab fa-twitter"></i></span>
                    </a>
                    <a href="#" class="icon instagram">
                        <div class="tooltip">Instagram</div>
                        <span><i class="fab fa-instagram"></i></span>
                    </a>
                    <a href="https://github.com" class="icon github">
                        <div class="tooltip">Github</div>
                        <span><i class="fab fa-github"></i></span>
                    </a>
                    <a href="https://www.youtube.com/@PhilippineEagleFoundation" class="icon youtube">
                        <div class="tooltip">Youtube</div>
                        <span><i class="fab fa-youtube"></i></span>
                    </a>
                </div>
            </div>
        </footer>
    </div>

    <?php if ($path): ?>
        <style>
        .dashboad-container {
            display: none;
        }
        </style>
        <div class="container">
            <?php require $path; ?>
        </div>
    <?php endif ?>
</body>
<script src="app.min.js"></script>
<script>
const get = new app();

document.addEventListener('DOMContentLoaded', () => {
    get.barGraph('barChart', <?php echo $data_json; ?>);
    get.pieGraph('doughnutChart', <?php echo $sitesDataJSON; ?>);
    get.barGraph('barChart2', <?php //echo json_encode($nursery); 
    ?>);
    get.pieGraph('doughnutChart2', <?php //echo json_encode($nurserySiteCount); 
    ?>);
})
</script>

</html>