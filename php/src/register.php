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

$error = '';
$password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
$passwordErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$email || !$password) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {

        if (strlen($password) < 8) {
            $passwordErrors[] = "At least 8 characters";
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $passwordErrors[] = "One uppercase letter";
        }

        if (!preg_match('/[a-z]/', $password)) {
            $passwordErrors[] = "One lowercase letter";
        }

        if (!preg_match('/[0-9]/', $password)) {
            $passwordErrors[] = "One number";
        }

        if (!preg_match('/[@$!%*?&]/', $password)) {
            $passwordErrors[] = "One special character";
        }

        if (!empty($passwordErrors)) {
            $error = "Password does not meet the requirements.";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hash]);
                header('Location: login.php?registered=1');
                exit;
            } catch (PDOException $e) {
                $error = 'Username or email already taken.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/navigation.css">
    <link rel="stylesheet" href="/css/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
  <?php include __DIR__ . '/includes/navigation.php'; ?>

  <main>
    <div class="register-wrapper">
        <div class="register-left">
            <h2 class="panel-heading">This is<br><em>for the record.</em></h2>
            <p class="panel-desc">Join thousands of collectors buying, selling and discovering vinyls, CDs and more.</p>
            <div class="panel-stats">
                <div class="stat-item">
                    <div class="stat-number">12k+</div>
                    <div class="stat-label">Listings</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">4k+</div>
                    <div class="stat-label">Sellers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">80+</div>
                    <div class="stat-label">Genres</div>
                </div>
            </div>
        </div>

        <div class="register-right">
            <div class="form-area">
                <p class="form-eyebrow">Get started</p>
                <h1 class="form-heading">Create your account</h1>
                <p class="form-sub">It's free and takes less than a minute.</p>

                <div id="error-msg" class="alert alert-danger" style="display:none;"></div>
                <div id="success-msg" class="alert alert-success" style="display:none;">Account created! Redirecting to login...</div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <div class="input-wrap">
                        <i class="bi bi-person"></i>
                        <input type="text" id="username" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope"></i>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="country">Country</label>
                    <div class="input-wrap">
                        <i class="bi bi-globe"></i>
                        <?= countrySelector($user['country'] ?? 'SG', 'country', 'country', 'form-control') ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="phone">Phone Number</label>
                    <div class="input-wrap">
                        <i class="bi bi-telephone"></i>
                        <input type="tel" name="phone" id="phone" class="form-control" placeholder="8123 4567" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="address">Delivery Address</label>
                    <div class="input-wrap">
                        <i class="bi bi-geo-alt"></i>
                        <input type="text" name="address" id="address" class="form-control" placeholder="e.g. 123 Orchard Road, Singapore" value="<?= htmlspecialchars($user['address'] ?? '') ?>" required>
                    </div>
                </div>
                            
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <ul class="password-checklist mt-2">
                        <li id="length">At least 8 characters</li>
                        <li id="upper">One uppercase letter</li>
                        <li id="lower">One lowercase letter</li>
                        <li id="number">One number</li>
                        <li id="special">One special character</li>
                    </ul>
                    
                    <button type="button" id="btn-register" class="btn-register">Create Account 
                        <i class="bi bi-arrow-right"></i>
                    </button>
                    
                    <p class="login-prompt">Already have an account? <a href="login.php">Sign in</a></p>
                </div>
            </div>
        </div>
    </div>
  </main>
  
  <!-- Bootstrap JavaScript -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

   <script>
        const password = document.getElementById("password");
        const checklist = document.querySelector(".password-checklist");

        password.addEventListener("focus", function(){
            checklist.style.display = "block";
        });

        password.addEventListener("blur", function(){
            if(password.value === ""){
                checklist.style.display = "none";
            }
        });

        password.addEventListener("keyup", function(){

            const value = password.value;

            document.getElementById("length").style.color =
                value.length >= 8 ? "green" : "red";

            document.getElementById("upper").style.color =
                /[A-Z]/.test(value) ? "green" : "red";

            document.getElementById("lower").style.color =
                /[a-z]/.test(value) ? "green" : "red";

            document.getElementById("number").style.color =
                /[0-9]/.test(value) ? "green" : "red";

            document.getElementById("special").style.color =
                /[@$!%*?&]/.test(value) ? "green" : "red";
        });
    </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script>
	// Geocoding function to convert address to coordinates
	async function getCoordinates(address, country) {
		try {
			// Get the country name from the country code
			const countryName = getCountryName(country); // You'll need this function
			
			// Format address more precisely
			const fullAddress = `${address}, ${countryName}`;
			const encodedAddress = encodeURIComponent(fullAddress);
			
			console.log('Geocoding address:', fullAddress);
			
			// Add more parameters for better accuracy
			const response = await fetch(
				`https://nominatim.openstreetmap.org/search?` +
				`format=json&q=${encodedAddress}&limit=1&` +
				`addressdetails=1&countrycodes=${country.toLowerCase()}`
			);
			const data = await response.json();
			
			if (data && data.length > 0) {
				console.log('Geocoding result:', data[0]);
				return {
					lat: parseFloat(data[0].lat),
					lon: parseFloat(data[0].lon)
				};
			} else {
				console.log('No geocoding results found');
			}
		} catch (error) {
			console.error('Geocoding failed:', error);
		}
		return null;
	}

	// Helper function to get country name from code
	function getCountryName(countryCode) {
		const countries = {
			'AD': 'Andorra',
			'AE': 'United Arab Emirates',
			'AF': 'Afghanistan',
			'AG': 'Antigua and Barbuda',
			'AI': 'Anguilla',
			'AL': 'Albania',
			'AM': 'Armenia',
			'AN': 'Netherlands Antilles',
			'AO': 'Angola',
			'AQ': 'Antarctica',
			'AR': 'Argentina',
			'AS': 'American Samoa',
			'AT': 'Austria',
			'AU': 'Australia',
			'AW': 'Aruba',
			'AZ': 'Azerbaijan',
			'BA': 'Bosnia and Herzegovina',
			'BB': 'Barbados',
			'BD': 'Bangladesh',
			'BE': 'Belgium',
			'BF': 'Burkina Faso',
			'BG': 'Bulgaria',
			'BH': 'Bahrain',
			'BI': 'Burundi',
			'BJ': 'Benin',
			'BL': 'Saint Barthelemy',
			'BM': 'Bermuda',
			'BN': 'Brunei Darussalam',
			'BO': 'Bolivia',
			'BR': 'Brazil',
			'BS': 'Bahamas',
			'BT': 'Bhutan',
			'BW': 'Botswana',
			'BY': 'Belarus',
			'BZ': 'Belize',
			'CA': 'Canada',
			'CC': 'Cocos (Keeling) Islands',
			'CD': 'Congo, The Democratic Republic of the',
			'CF': 'Central African Republic',
			'CG': 'Congo',
			'CH': 'Switzerland',
			'CI': 'Cote D Ivoire',
			'CK': 'Cook Islands',
			'CL': 'Chile',
			'CM': 'Cameroon',
			'CN': 'China',
			'CO': 'Colombia',
			'CR': 'Costa Rica',
			'CU': 'Cuba',
			'CV': 'Cape Verde',
			'CX': 'Christmas Island',
			'CY': 'Cyprus',
			'CZ': 'Czech Republic',
			'DE': 'Germany',
			'DJ': 'Djibouti',
			'DK': 'Denmark',
			'DM': 'Dominica',
			'DO': 'Dominican Republic',
			'DZ': 'Algeria',
			'EC': 'Ecuador',
			'EE': 'Estonia',
			'EG': 'Egypt',
			'ER': 'Eritrea',
			'ES': 'Spain',
			'ET': 'Ethiopia',
			'FI': 'Finland',
			'FJ': 'Fiji',
			'FK': 'Falkland Islands (Malvinas)',
			'FM': 'Micronesia, Federated States of',
			'FO': 'Faroe Islands',
			'FR': 'France',
			'GA': 'Gabon',
			'GB': 'United Kingdom',
			'GD': 'Grenada',
			'GE': 'Georgia',
			'GH': 'Ghana',
			'GI': 'Gibraltar',
			'GL': 'Greenland',
			'GM': 'Gambia',
			'GN': 'Guinea',
			'GQ': 'Equatorial Guinea',
			'GR': 'Greece',
			'GT': 'Guatemala',
			'GU': 'Guam',
			'GW': 'Guinea-Bissau',
			'GY': 'Guyana',
			'HK': 'Hong Kong',
			'HN': 'Honduras',
			'HR': 'Croatia',
			'HT': 'Haiti',
			'HU': 'Hungary',
			'ID': 'Indonesia',
			'IE': 'Ireland',
			'IL': 'Israel',
			'IM': 'Isle of Man',
			'IN': 'India',
			'IQ': 'Iraq',
			'IR': 'Iran, Islamic Republic of',
			'IS': 'Iceland',
			'IT': 'Italy',
			'JM': 'Jamaica',
			'JO': 'Jordan',
			'JP': 'Japan',
			'KE': 'Kenya',
			'KG': 'Kyrgyzstan',
			'KH': 'Cambodia',
			'KI': 'Kiribati',
			'KM': 'Comoros',
			'KN': 'Saint Kitts and Nevis',
			'KP': 'Korea Democratic Peoples Republic of',
			'KR': 'Korea Republic of',
			'KW': 'Kuwait',
			'KY': 'Cayman Islands',
			'KZ': 'Kazakstan',
			'LA': 'Lao Peoples Democratic Republic',
			'LB': 'Lebanon',
			'LC': 'Saint Lucia',
			'LI': 'Liechtenstein',
			'LK': 'Sri Lanka',
			'LR': 'Liberia',
			'LS': 'Lesotho',
			'LT': 'Lithuania',
			'LU': 'Luxembourg',
			'LV': 'Latvia',
			'LY': 'Libyan Arab Jamahiriya',
			'MA': 'Morocco',
			'MC': 'Monaco',
			'MD': 'Moldova, Republic of',
			'ME': 'Montenegro',
			'MF': 'Saint Martin',
			'MG': 'Madagascar',
			'MH': 'Marshall Islands',
			'MK': 'Macedonia, The Former Yugoslav Republic of',
			'ML': 'Mali',
			'MM': 'Myanmar',
			'MN': 'Mongolia',
			'MO': 'Macau',
			'MP': 'Northern Mariana Islands',
			'MR': 'Mauritania',
			'MS': 'Montserrat',
			'MT': 'Malta',
			'MU': 'Mauritius',
			'MV': 'Maldives',
			'MW': 'Malawi',
			'MX': 'Mexico',
			'MY': 'Malaysia',
			'MZ': 'Mozambique',
			'NA': 'Namibia',
			'NC': 'New Caledonia',
			'NE': 'Niger',
			'NG': 'Nigeria',
			'NI': 'Nicaragua',
			'NL': 'Netherlands',
			'NO': 'Norway',
			'NP': 'Nepal',
			'NR': 'Nauru',
			'NU': 'Niue',
			'NZ': 'New Zealand',
			'OM': 'Oman',
			'PA': 'Panama',
			'PE': 'Peru',
			'PF': 'French Polynesia',
			'PG': 'Papua New Guinea',
			'PH': 'Philippines',
			'PK': 'Pakistan',
			'PL': 'Poland',
			'PM': 'Saint Pierre and Miquelon',
			'PN': 'Pitcairn',
			'PR': 'Puerto Rico',
			'PT': 'Portugal',
			'PW': 'Palau',
			'PY': 'Paraguay',
			'QA': 'Qatar',
			'RO': 'Romania',
			'RS': 'Serbia',
			'RU': 'Russian Federation',
			'RW': 'Rwanda',
			'SA': 'Saudi Arabia',
			'SB': 'Solomon Islands',
			'SC': 'Seychelles',
			'SD': 'Sudan',
			'SE': 'Sweden',
			'SG': 'Singapore',
			'SH': 'Saint Helena',
			'SI': 'Slovenia',
			'SK': 'Slovakia',
			'SL': 'Sierra Leone',
			'SM': 'San Marino',
			'SN': 'Senegal',
			'SO': 'Somalia',
			'SR': 'Suriname',
			'ST': 'Sao Tome and Principe',
			'SV': 'El Salvador',
			'SY': 'Syrian Arab Republic',
			'SZ': 'Swaziland',
			'TC': 'Turks and Caicos Islands',
			'TD': 'Chad',
			'TG': 'Togo',
			'TH': 'Thailand',
			'TJ': 'Tajikistan',
			'TK': 'Tokelau',
			'TL': 'Timor-Leste',
			'TM': 'Turkmenistan',
			'TN': 'Tunisia',
			'TO': 'Tonga',
			'TR': 'Turkey',
			'TT': 'Trinidad and Tobago',
			'TV': 'Tuvalu',
			'TW': 'Taiwan, Province of China',
			'TZ': 'Tanzania, United Republic of',
			'UA': 'Ukraine',
			'UG': 'Uganda',
			'US': 'United States',
			'UY': 'Uruguay',
			'UZ': 'Uzbekistan',
			'VA': 'Holy See (Vatican City State)',
			'VC': 'Saint Vincent and The Grenadines',
			'VE': 'Venezuela',
			'VG': 'Virgin Islands, British',
			'VI': 'Virgin Islands, U.S.',
			'VN': 'Viet Nam',
			'VU': 'Vanuatu',
			'WF': 'Wallis and Futuna',
			'WS': 'Samoa',
			'XK': 'Kosovo',
			'YE': 'Yemen',
			'YT': 'Mayotte',
			'ZA': 'South Africa',
			'ZM': 'Zambia',
			'ZW': 'Zimbabwe'
		};
		return countries[countryCode] || countryCode;
	}

	// Make the click handler async
	document.getElementById('btn-register').addEventListener('click', async function () {
		const username = document.getElementById('username').value.trim();
		const email    = document.getElementById('email').value.trim();
		const password = document.getElementById('password').value;
		const phone    = document.getElementById('phone').value.trim();
		const address  = document.getElementById('address').value.trim();
		const country  = document.getElementById('country').value;
		const errorDiv   = document.getElementById('error-msg');
		const successDiv = document.getElementById('success-msg');

		errorDiv.style.display   = 'none';
		successDiv.style.display = 'none';

		// Check all required fields
		if (!username) {
			errorDiv.textContent = 'Username is required.';
			errorDiv.style.display = 'block';
			return;
		}
		
		if (!email) {
			errorDiv.textContent = 'Email is required.';
			errorDiv.style.display = 'block';
			return;
		}
		
		// Email format validation
		const emailPattern = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
		if (!emailPattern.test(email)) {
			errorDiv.textContent = 'Please enter a valid email address (e.g., name@example.com).';
			errorDiv.style.display = 'block';
			return;
		}
		
		if (!phone) {
			errorDiv.textContent = 'Phone number is required.';
			errorDiv.style.display = 'block';
			return;
		}
		
		if (!address) {
			errorDiv.textContent = 'Delivery address is required.';
			errorDiv.style.display = 'block';
			return;
		}
		
		if (!password) {
			errorDiv.textContent = 'Password is required.';
			errorDiv.style.display = 'block';
			return;
		}

		// Validate password strength
		const passwordErrors = [];
		if (password.length < 8) passwordErrors.push("At least 8 characters");
		if (!/[A-Z]/.test(password)) passwordErrors.push("One uppercase letter");
		if (!/[a-z]/.test(password)) passwordErrors.push("One lowercase letter");
		if (!/[0-9]/.test(password)) passwordErrors.push("One number");
		if (!/[@$!%*?&]/.test(password)) passwordErrors.push("One special character");
		
		if (passwordErrors.length > 0) {
			errorDiv.textContent = "Password requirements: " + passwordErrors.join(", ");
			errorDiv.style.display = 'block';
			return;
		}

		// Phone number format validation (basic)
		const phonePattern = /^[0-9+\-\s()]+$/;
		if (!phonePattern.test(phone)) {
			errorDiv.textContent = 'Please enter a valid phone number.';
			errorDiv.style.display = 'block';
			return;
		}

		// Show loading state
		const registerBtn = document.getElementById('btn-register');
		const originalBtnText = registerBtn.innerHTML;
		registerBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Getting location...';
		registerBtn.disabled = true;

		// Get coordinates from address
		let latitude = null;
		let longitude = null;
		
		try {
			errorDiv.textContent = 'Geocoding address...';
			errorDiv.style.display = 'block';
			errorDiv.style.backgroundColor = '#fff3cd';
			errorDiv.style.color = '#856404';
			
			const coords = await getCoordinates(address, country);
			if (coords) {
				latitude = coords.lat;
				longitude = coords.lon;
				errorDiv.textContent = '✓ Address located! Registering...';
			} else {
				errorDiv.textContent = 'Could not locate address. Registering without location...';
			}
		} catch (error) {
			console.error('Geocoding error:', error);
			errorDiv.textContent = 'Location service error. Registering without location...';
		}

		// Prepare form data
		const body = new FormData();
		body.append('csrf_token', '<?php echo Security::generateCSRFToken(); ?>');
		body.append('username', username);
		body.append('email', email);
		body.append('password', password);
		body.append('phone', phone);
		body.append('address', address);
		body.append('country', country);
		
		// Add coordinates if available
		if (latitude && longitude) {
			body.append('latitude', latitude);
			body.append('longitude', longitude);
			console.log(`Coordinates added: ${latitude}, ${longitude}`);
		} else {
			console.log('No coordinates available');
		}

		// Update button text
		registerBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Creating account...';

		fetch('/api/register.php', { method: 'POST', body })
			.then(r => r.json())
			.then(data => {
				if (data.status === 'ok') {
					successDiv.style.display = 'block';
					errorDiv.style.display = 'none';
					setTimeout(() => { 
						window.location.href = 'login.php?registered=1'; 
					}, 1200);
				} else {
					errorDiv.textContent = data.error || 'Registration failed.';
					errorDiv.style.display = 'block';
					errorDiv.style.backgroundColor = '#f8d7da';
					errorDiv.style.color = '#721c24';
					registerBtn.innerHTML = originalBtnText;
					registerBtn.disabled = false;
				}
			})
			.catch((error) => {
				console.error('Fetch error:', error);
				errorDiv.textContent = 'An error occurred. Please try again.';
				errorDiv.style.display = 'block';
				errorDiv.style.backgroundColor = '#f8d7da';
				errorDiv.style.color = '#721c24';
				registerBtn.innerHTML = originalBtnText;
				registerBtn.disabled = false;
			});
	});
	</script>
</body>
</html>
