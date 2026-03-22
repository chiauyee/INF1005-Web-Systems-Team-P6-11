<?php
session_start();
require 'db.php';

$countryArray = array(
	'AD'=>array('name'=>'ANDORRA','code'=>'376'),
	'AE'=>array('name'=>'UNITED ARAB EMIRATES','code'=>'971'),
	'AF'=>array('name'=>'AFGHANISTAN','code'=>'93'),
	'AG'=>array('name'=>'ANTIGUA AND BARBUDA','code'=>'1268'),
	'AI'=>array('name'=>'ANGUILLA','code'=>'1264'),
	'AL'=>array('name'=>'ALBANIA','code'=>'355'),
	'AM'=>array('name'=>'ARMENIA','code'=>'374'),
	'AN'=>array('name'=>'NETHERLANDS ANTILLES','code'=>'599'),
	'AO'=>array('name'=>'ANGOLA','code'=>'244'),
	'AQ'=>array('name'=>'ANTARCTICA','code'=>'672'),
	'AR'=>array('name'=>'ARGENTINA','code'=>'54'),
	'AS'=>array('name'=>'AMERICAN SAMOA','code'=>'1684'),
	'AT'=>array('name'=>'AUSTRIA','code'=>'43'),
	'AU'=>array('name'=>'AUSTRALIA','code'=>'61'),
	'AW'=>array('name'=>'ARUBA','code'=>'297'),
	'AZ'=>array('name'=>'AZERBAIJAN','code'=>'994'),
	'BA'=>array('name'=>'BOSNIA AND HERZEGOVINA','code'=>'387'),
	'BB'=>array('name'=>'BARBADOS','code'=>'1246'),
	'BD'=>array('name'=>'BANGLADESH','code'=>'880'),
	'BE'=>array('name'=>'BELGIUM','code'=>'32'),
	'BF'=>array('name'=>'BURKINA FASO','code'=>'226'),
	'BG'=>array('name'=>'BULGARIA','code'=>'359'),
	'BH'=>array('name'=>'BAHRAIN','code'=>'973'),
	'BI'=>array('name'=>'BURUNDI','code'=>'257'),
	'BJ'=>array('name'=>'BENIN','code'=>'229'),
	'BL'=>array('name'=>'SAINT BARTHELEMY','code'=>'590'),
	'BM'=>array('name'=>'BERMUDA','code'=>'1441'),
	'BN'=>array('name'=>'BRUNEI DARUSSALAM','code'=>'673'),
	'BO'=>array('name'=>'BOLIVIA','code'=>'591'),
	'BR'=>array('name'=>'BRAZIL','code'=>'55'),
	'BS'=>array('name'=>'BAHAMAS','code'=>'1242'),
	'BT'=>array('name'=>'BHUTAN','code'=>'975'),
	'BW'=>array('name'=>'BOTSWANA','code'=>'267'),
	'BY'=>array('name'=>'BELARUS','code'=>'375'),
	'BZ'=>array('name'=>'BELIZE','code'=>'501'),
	'CA'=>array('name'=>'CANADA','code'=>'1'),
	'CC'=>array('name'=>'COCOS (KEELING) ISLANDS','code'=>'61'),
	'CD'=>array('name'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE','code'=>'243'),
	'CF'=>array('name'=>'CENTRAL AFRICAN REPUBLIC','code'=>'236'),
	'CG'=>array('name'=>'CONGO','code'=>'242'),
	'CH'=>array('name'=>'SWITZERLAND','code'=>'41'),
	'CI'=>array('name'=>'COTE D IVOIRE','code'=>'225'),
	'CK'=>array('name'=>'COOK ISLANDS','code'=>'682'),
	'CL'=>array('name'=>'CHILE','code'=>'56'),
	'CM'=>array('name'=>'CAMEROON','code'=>'237'),
	'CN'=>array('name'=>'CHINA','code'=>'86'),
	'CO'=>array('name'=>'COLOMBIA','code'=>'57'),
	'CR'=>array('name'=>'COSTA RICA','code'=>'506'),
	'CU'=>array('name'=>'CUBA','code'=>'53'),
	'CV'=>array('name'=>'CAPE VERDE','code'=>'238'),
	'CX'=>array('name'=>'CHRISTMAS ISLAND','code'=>'61'),
	'CY'=>array('name'=>'CYPRUS','code'=>'357'),
	'CZ'=>array('name'=>'CZECH REPUBLIC','code'=>'420'),
	'DE'=>array('name'=>'GERMANY','code'=>'49'),
	'DJ'=>array('name'=>'DJIBOUTI','code'=>'253'),
	'DK'=>array('name'=>'DENMARK','code'=>'45'),
	'DM'=>array('name'=>'DOMINICA','code'=>'1767'),
	'DO'=>array('name'=>'DOMINICAN REPUBLIC','code'=>'1809'),
	'DZ'=>array('name'=>'ALGERIA','code'=>'213'),
	'EC'=>array('name'=>'ECUADOR','code'=>'593'),
	'EE'=>array('name'=>'ESTONIA','code'=>'372'),
	'EG'=>array('name'=>'EGYPT','code'=>'20'),
	'ER'=>array('name'=>'ERITREA','code'=>'291'),
	'ES'=>array('name'=>'SPAIN','code'=>'34'),
	'ET'=>array('name'=>'ETHIOPIA','code'=>'251'),
	'FI'=>array('name'=>'FINLAND','code'=>'358'),
	'FJ'=>array('name'=>'FIJI','code'=>'679'),
	'FK'=>array('name'=>'FALKLAND ISLANDS (MALVINAS)','code'=>'500'),
	'FM'=>array('name'=>'MICRONESIA, FEDERATED STATES OF','code'=>'691'),
	'FO'=>array('name'=>'FAROE ISLANDS','code'=>'298'),
	'FR'=>array('name'=>'FRANCE','code'=>'33'),
	'GA'=>array('name'=>'GABON','code'=>'241'),
	'GB'=>array('name'=>'UNITED KINGDOM','code'=>'44'),
	'GD'=>array('name'=>'GRENADA','code'=>'1473'),
	'GE'=>array('name'=>'GEORGIA','code'=>'995'),
	'GH'=>array('name'=>'GHANA','code'=>'233'),
	'GI'=>array('name'=>'GIBRALTAR','code'=>'350'),
	'GL'=>array('name'=>'GREENLAND','code'=>'299'),
	'GM'=>array('name'=>'GAMBIA','code'=>'220'),
	'GN'=>array('name'=>'GUINEA','code'=>'224'),
	'GQ'=>array('name'=>'EQUATORIAL GUINEA','code'=>'240'),
	'GR'=>array('name'=>'GREECE','code'=>'30'),
	'GT'=>array('name'=>'GUATEMALA','code'=>'502'),
	'GU'=>array('name'=>'GUAM','code'=>'1671'),
	'GW'=>array('name'=>'GUINEA-BISSAU','code'=>'245'),
	'GY'=>array('name'=>'GUYANA','code'=>'592'),
	'HK'=>array('name'=>'HONG KONG','code'=>'852'),
	'HN'=>array('name'=>'HONDURAS','code'=>'504'),
	'HR'=>array('name'=>'CROATIA','code'=>'385'),
	'HT'=>array('name'=>'HAITI','code'=>'509'),
	'HU'=>array('name'=>'HUNGARY','code'=>'36'),
	'ID'=>array('name'=>'INDONESIA','code'=>'62'),
	'IE'=>array('name'=>'IRELAND','code'=>'353'),
	'IL'=>array('name'=>'ISRAEL','code'=>'972'),
	'IM'=>array('name'=>'ISLE OF MAN','code'=>'44'),
	'IN'=>array('name'=>'INDIA','code'=>'91'),
	'IQ'=>array('name'=>'IRAQ','code'=>'964'),
	'IR'=>array('name'=>'IRAN, ISLAMIC REPUBLIC OF','code'=>'98'),
	'IS'=>array('name'=>'ICELAND','code'=>'354'),
	'IT'=>array('name'=>'ITALY','code'=>'39'),
	'JM'=>array('name'=>'JAMAICA','code'=>'1876'),
	'JO'=>array('name'=>'JORDAN','code'=>'962'),
	'JP'=>array('name'=>'JAPAN','code'=>'81'),
	'KE'=>array('name'=>'KENYA','code'=>'254'),
	'KG'=>array('name'=>'KYRGYZSTAN','code'=>'996'),
	'KH'=>array('name'=>'CAMBODIA','code'=>'855'),
	'KI'=>array('name'=>'KIRIBATI','code'=>'686'),
	'KM'=>array('name'=>'COMOROS','code'=>'269'),
	'KN'=>array('name'=>'SAINT KITTS AND NEVIS','code'=>'1869'),
	'KP'=>array('name'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','code'=>'850'),
	'KR'=>array('name'=>'KOREA REPUBLIC OF','code'=>'82'),
	'KW'=>array('name'=>'KUWAIT','code'=>'965'),
	'KY'=>array('name'=>'CAYMAN ISLANDS','code'=>'1345'),
	'KZ'=>array('name'=>'KAZAKSTAN','code'=>'7'),
	'LA'=>array('name'=>'LAO PEOPLES DEMOCRATIC REPUBLIC','code'=>'856'),
	'LB'=>array('name'=>'LEBANON','code'=>'961'),
	'LC'=>array('name'=>'SAINT LUCIA','code'=>'1758'),
	'LI'=>array('name'=>'LIECHTENSTEIN','code'=>'423'),
	'LK'=>array('name'=>'SRI LANKA','code'=>'94'),
	'LR'=>array('name'=>'LIBERIA','code'=>'231'),
	'LS'=>array('name'=>'LESOTHO','code'=>'266'),
	'LT'=>array('name'=>'LITHUANIA','code'=>'370'),
	'LU'=>array('name'=>'LUXEMBOURG','code'=>'352'),
	'LV'=>array('name'=>'LATVIA','code'=>'371'),
	'LY'=>array('name'=>'LIBYAN ARAB JAMAHIRIYA','code'=>'218'),
	'MA'=>array('name'=>'MOROCCO','code'=>'212'),
	'MC'=>array('name'=>'MONACO','code'=>'377'),
	'MD'=>array('name'=>'MOLDOVA, REPUBLIC OF','code'=>'373'),
	'ME'=>array('name'=>'MONTENEGRO','code'=>'382'),
	'MF'=>array('name'=>'SAINT MARTIN','code'=>'1599'),
	'MG'=>array('name'=>'MADAGASCAR','code'=>'261'),
	'MH'=>array('name'=>'MARSHALL ISLANDS','code'=>'692'),
	'MK'=>array('name'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','code'=>'389'),
	'ML'=>array('name'=>'MALI','code'=>'223'),
	'MM'=>array('name'=>'MYANMAR','code'=>'95'),
	'MN'=>array('name'=>'MONGOLIA','code'=>'976'),
	'MO'=>array('name'=>'MACAU','code'=>'853'),
	'MP'=>array('name'=>'NORTHERN MARIANA ISLANDS','code'=>'1670'),
	'MR'=>array('name'=>'MAURITANIA','code'=>'222'),
	'MS'=>array('name'=>'MONTSERRAT','code'=>'1664'),
	'MT'=>array('name'=>'MALTA','code'=>'356'),
	'MU'=>array('name'=>'MAURITIUS','code'=>'230'),
	'MV'=>array('name'=>'MALDIVES','code'=>'960'),
	'MW'=>array('name'=>'MALAWI','code'=>'265'),
	'MX'=>array('name'=>'MEXICO','code'=>'52'),
	'MY'=>array('name'=>'MALAYSIA','code'=>'60'),
	'MZ'=>array('name'=>'MOZAMBIQUE','code'=>'258'),
	'NA'=>array('name'=>'NAMIBIA','code'=>'264'),
	'NC'=>array('name'=>'NEW CALEDONIA','code'=>'687'),
	'NE'=>array('name'=>'NIGER','code'=>'227'),
	'NG'=>array('name'=>'NIGERIA','code'=>'234'),
	'NI'=>array('name'=>'NICARAGUA','code'=>'505'),
	'NL'=>array('name'=>'NETHERLANDS','code'=>'31'),
	'NO'=>array('name'=>'NORWAY','code'=>'47'),
	'NP'=>array('name'=>'NEPAL','code'=>'977'),
	'NR'=>array('name'=>'NAURU','code'=>'674'),
	'NU'=>array('name'=>'NIUE','code'=>'683'),
	'NZ'=>array('name'=>'NEW ZEALAND','code'=>'64'),
	'OM'=>array('name'=>'OMAN','code'=>'968'),
	'PA'=>array('name'=>'PANAMA','code'=>'507'),
	'PE'=>array('name'=>'PERU','code'=>'51'),
	'PF'=>array('name'=>'FRENCH POLYNESIA','code'=>'689'),
	'PG'=>array('name'=>'PAPUA NEW GUINEA','code'=>'675'),
	'PH'=>array('name'=>'PHILIPPINES','code'=>'63'),
	'PK'=>array('name'=>'PAKISTAN','code'=>'92'),
	'PL'=>array('name'=>'POLAND','code'=>'48'),
	'PM'=>array('name'=>'SAINT PIERRE AND MIQUELON','code'=>'508'),
	'PN'=>array('name'=>'PITCAIRN','code'=>'870'),
	'PR'=>array('name'=>'PUERTO RICO','code'=>'1'),
	'PT'=>array('name'=>'PORTUGAL','code'=>'351'),
	'PW'=>array('name'=>'PALAU','code'=>'680'),
	'PY'=>array('name'=>'PARAGUAY','code'=>'595'),
	'QA'=>array('name'=>'QATAR','code'=>'974'),
	'RO'=>array('name'=>'ROMANIA','code'=>'40'),
	'RS'=>array('name'=>'SERBIA','code'=>'381'),
	'RU'=>array('name'=>'RUSSIAN FEDERATION','code'=>'7'),
	'RW'=>array('name'=>'RWANDA','code'=>'250'),
	'SA'=>array('name'=>'SAUDI ARABIA','code'=>'966'),
	'SB'=>array('name'=>'SOLOMON ISLANDS','code'=>'677'),
	'SC'=>array('name'=>'SEYCHELLES','code'=>'248'),
	'SD'=>array('name'=>'SUDAN','code'=>'249'),
	'SE'=>array('name'=>'SWEDEN','code'=>'46'),
	'SG'=>array('name'=>'SINGAPORE','code'=>'65'),
	'SH'=>array('name'=>'SAINT HELENA','code'=>'290'),
	'SI'=>array('name'=>'SLOVENIA','code'=>'386'),
	'SK'=>array('name'=>'SLOVAKIA','code'=>'421'),
	'SL'=>array('name'=>'SIERRA LEONE','code'=>'232'),
	'SM'=>array('name'=>'SAN MARINO','code'=>'378'),
	'SN'=>array('name'=>'SENEGAL','code'=>'221'),
	'SO'=>array('name'=>'SOMALIA','code'=>'252'),
	'SR'=>array('name'=>'SURINAME','code'=>'597'),
	'ST'=>array('name'=>'SAO TOME AND PRINCIPE','code'=>'239'),
	'SV'=>array('name'=>'EL SALVADOR','code'=>'503'),
	'SY'=>array('name'=>'SYRIAN ARAB REPUBLIC','code'=>'963'),
	'SZ'=>array('name'=>'SWAZILAND','code'=>'268'),
	'TC'=>array('name'=>'TURKS AND CAICOS ISLANDS','code'=>'1649'),
	'TD'=>array('name'=>'CHAD','code'=>'235'),
	'TG'=>array('name'=>'TOGO','code'=>'228'),
	'TH'=>array('name'=>'THAILAND','code'=>'66'),
	'TJ'=>array('name'=>'TAJIKISTAN','code'=>'992'),
	'TK'=>array('name'=>'TOKELAU','code'=>'690'),
	'TL'=>array('name'=>'TIMOR-LESTE','code'=>'670'),
	'TM'=>array('name'=>'TURKMENISTAN','code'=>'993'),
	'TN'=>array('name'=>'TUNISIA','code'=>'216'),
	'TO'=>array('name'=>'TONGA','code'=>'676'),
	'TR'=>array('name'=>'TURKEY','code'=>'90'),
	'TT'=>array('name'=>'TRINIDAD AND TOBAGO','code'=>'1868'),
	'TV'=>array('name'=>'TUVALU','code'=>'688'),
	'TW'=>array('name'=>'TAIWAN, PROVINCE OF CHINA','code'=>'886'),
	'TZ'=>array('name'=>'TANZANIA, UNITED REPUBLIC OF','code'=>'255'),
	'UA'=>array('name'=>'UKRAINE','code'=>'380'),
	'UG'=>array('name'=>'UGANDA','code'=>'256'),
	'US'=>array('name'=>'UNITED STATES','code'=>'1'),
	'UY'=>array('name'=>'URUGUAY','code'=>'598'),
	'UZ'=>array('name'=>'UZBEKISTAN','code'=>'998'),
	'VA'=>array('name'=>'HOLY SEE (VATICAN CITY STATE)','code'=>'39'),
	'VC'=>array('name'=>'SAINT VINCENT AND THE GRENADINES','code'=>'1784'),
	'VE'=>array('name'=>'VENEZUELA','code'=>'58'),
	'VG'=>array('name'=>'VIRGIN ISLANDS, BRITISH','code'=>'1284'),
	'VI'=>array('name'=>'VIRGIN ISLANDS, U.S.','code'=>'1340'),
	'VN'=>array('name'=>'VIET NAM','code'=>'84'),
	'VU'=>array('name'=>'VANUATU','code'=>'678'),
	'WF'=>array('name'=>'WALLIS AND FUTUNA','code'=>'681'),
	'WS'=>array('name'=>'SAMOA','code'=>'685'),
	'XK'=>array('name'=>'KOSOVO','code'=>'381'),
	'YE'=>array('name'=>'YEMEN','code'=>'967'),
	'YT'=>array('name'=>'MAYOTTE','code'=>'262'),
	'ZA'=>array('name'=>'SOUTH AFRICA','code'=>'27'),
	'ZM'=>array('name'=>'ZAMBIA','code'=>'260'),
	'ZW'=>array('name'=>'ZIMBABWE','code'=>'263')
);

function countrySelector($defaultCountry = "", $id = "", $name = "", $classes = ""){
    global $countryArray;
    
    $output = "<select id='".$id."' name='".$name."' class='".$classes."'>";
	
	foreach($countryArray as $code => $country){
		$countryName = ucwords(strtolower($country["name"])); // Making it look good
		$output .= "<option value='".$code."' ".(($code==strtoupper($defaultCountry))?"selected":"").">".$code." - ".$countryName." (+".$country["code"].")</option>";
	}
	
	$output .= "</select>";
	
	return $output;
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $phone   = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $country = $_POST['country'] ?? '';

    $stmt = $pdo->prepare("UPDATE users SET phone = ?, address = ?, country = ? WHERE id = ?");

    if ($stmt->execute([$phone, $address, $country, $_SESSION['user_id']])) {
        $success = 'Profile updated successfully.';
    } else {
        $error = 'Something went wrong. Please try again.';
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Purchases: listings bought by this user
$stmt = $pdo->prepare("
    SELECT l.listing_id, l.price, l.created_at,
           al.album_name, ar.artist_name,
           s.username AS seller_username
    FROM listings l
    JOIN albums al ON l.album_mbid = al.album_mbid
    JOIN artists ar ON al.artist_mbid = ar.artist_mbid
    JOIN users s ON l.seller_id = s.id
    WHERE l.buyer_id = ? AND l.status = 'complete'
    ORDER BY l.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Sales: listings created by this user
$stmt = $pdo->prepare("
    SELECT l.listing_id, l.price, l.status, l.created_at,
           al.album_name, ar.artist_name,
           b.username AS buyer_username
    FROM listings l
    JOIN albums al ON l.album_mbid = al.album_mbid
    JOIN artists ar ON al.artist_mbid = ar.artist_mbid
    LEFT JOIN users b ON l.buyer_id = b.id
    WHERE l.seller_id = ?
    ORDER BY FIELD(l.status, 'complete', 'available', 'pending', 'rejected'), l.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$my_listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="/css/navigation.css">
    <link rel="stylesheet" href="/css/profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    
</head>

<body>
    <?php include __DIR__ . '/includes/navigation.php'; ?>

    <main class="profile-page">
        <div class="profile-header">
            <div class="avatar">
                <?= strtoupper(substr(htmlspecialchars($user['username']), 0, 1)) ?>
            </div>
            <div class="profile-header-info">
                <p class="profile-eyebrow">Member Profile</p>
                <h1 class="profile-name"><?= htmlspecialchars($user['username']) ?></h1>
            </div>
        </div>

        <div class="section-card">
            <h2 class="section-title">Contact Details</h2>

            <?php if ($success): ?>
                <div class="alert-success">
                    <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert-danger">
                    <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="field-row">
                    <div class="field-group">
                        <label class="field-label" for="email">Email</label>
                        <div class="field-input-wrap">
                            <i class="bi bi-envelope"></i>
                            <input type="email" id="email" class="field-input" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="date">Date Joined</label>
                        <div class="field-input-wrap">
                            <i class="bi bi-calendar3"></i>
                            <input type="text" id="date" class="field-input" value="<?= date('d F Y', strtotime($user['created_at'])) ?>" disabled>
                        </div>
                    </div>
                    
                    <div class="field-group">
                        <label class="field-label" for="country">Country</label>
                        <div class="field-input-wrap">
                            <i class="bi bi-globe"></i>
                            <?= countrySelector($user['country'] ?? 'SG', 'country', 'country', 'field-input') ?>
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="phone">Phone Number</label>
                        <div class="field-input-wrap">
                            <i class="bi bi-telephone"></i>
                            <input type="tel" name="phone" id="phone"
                                class="field-input"
                                placeholder="8123 4567"
                                value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="address">Delivery Address</label>
                        <div class="field-input-wrap">
                            <i class="bi bi-geo-alt"></i>
                            <input type="text" name="address" id="address" class="field-input" placeholder="e.g. 123 Orchard Road, Singapore" value="<?= htmlspecialchars($user['address'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-dark-custom mt-3">
                    <i class="bi bi-check2"></i> Save Changes
                </button>
            </form>
        </div>

        <div class="section-card">
            <h2 class="section-title">Recent Orders</h2>
            <?php if (empty($purchases)): ?>
                <div class="empty-state">
                    <i class="bi bi-bag"></i>No orders yet. <a href="listings.php">Start shopping</a>
                </div>
            <?php else: ?>
                <div class="order-list">
                    <?php foreach ($purchases as $p): ?>
                    <div class="order-row-profile">
                        <div class="order-info">
                            <div class="order-album-name"><?= htmlspecialchars($p['album_name']) ?></div>
                            <div class="order-meta-text"><?= htmlspecialchars($p['artist_name']) ?> &middot; Seller: <?= htmlspecialchars($p['seller_username']) ?></div>
                            <div class="order-date"><?= date('d M Y', strtotime($p['created_at'])) ?></div>
                        </div>
                        <div class="order-right">
                            <div class="order-price-val">$<?= number_format((float)$p['price'], 2) ?></div>
                            <span class="status-badge status-complete">Purchased</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="section-card">
            <h2 class="section-title">My Listings</h2>
            <?php if (empty($my_listings)): ?>
                <div class="empty-state">
                    <i class="bi bi-vinyl"></i>No listings yet. <a href="make_listing.php">Sell a record</a>
                </div>
            <?php else: ?>
                <div class="order-list">
                    <?php foreach ($my_listings as $l): ?>
                    <div class="order-row-profile">
                        <div class="order-info">
                            <div class="order-album-name"><?= htmlspecialchars($l['album_name']) ?></div>
                            <div class="order-meta-text"><?= htmlspecialchars($l['artist_name']) ?>
                                <?php if ($l['status'] === 'complete' && $l['buyer_username']): ?>
                                    &middot; Sold to: <?= htmlspecialchars($l['buyer_username']) ?>
                                <?php endif; ?>
                            </div>
                            <div class="order-date"><?= date('d M Y', strtotime($l['created_at'])) ?></div>
                        </div>
                        <div class="order-right">
                            <div class="order-price-val">$<?= number_format((float)$l['price'], 2) ?></div>
                            <?php
                                $badgeClass = match($l['status']) {
                                    'available' => 'status-available',
                                    'complete'  => 'status-complete',
                                    'rejected'  => 'status-rejected',
                                    default     => 'status-pending',
                                };
                                $badgeLabel = match($l['status']) {
                                    'available' => 'Available',
                                    'complete'  => 'Sold',
                                    'rejected'  => 'Rejected',
                                    default     => 'Pending',
                                };
                            ?>
                            <span class="status-badge <?= $badgeClass ?>"><?= $badgeLabel ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="profile-actions">
            <a href="index.php" class="btn-dark-custom">
                <i class="bi bi-house"></i> Back to Home
            </a>
            <a href="logout.php" class="btn-outline-custom">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>

	</main>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>