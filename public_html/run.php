<?php

set_time_limit(60);

include __DIR__ . '/../vendor/autoload.php';

// create a log channel
$log = new \Monolog\Logger('clanReserves');
$log->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/../var/cron.log', \Monolog\Logger::DEBUG));

// Uruchamiane co 1min
$log->info('Start');

$now = godzina(date('H'), date('i'));
$start = godzina(10, 45);
$end = godzina(23, 45);

if ($now <= $start) {
	$log->info(sprintf('PRZERWANE, przed %d:%d', intdiv($start, 60), $start % 60));
	die();
}

if ($now >= $end) {
	$log->info(sprintf('PRZERWANE, po %d:%d', intdiv($end, 60), $end % 60));
	die();
}

function godzina(int $h, int $m): int
{
	return ($h * 60) + $m;
}

function zaIleSekundOdpal(stdClass $obiektRezerwy): int
{
	foreach ($obiektRezerwy->in_stock as $rezerwa) {
		if ($rezerwa->status === 'active') {
			return $rezerwa->active_till - time();
		}
	}

	// Nieznaleziono, a powinbyc
	return 0;
}


$accessTokenStore = new \hwao\WargamingClanBot\AccessTokenStore(__DIR__ . '/../var/accessToken.php');

$config = (array) include __DIR__ . '/../config.php';
$application_id = $config['application_id'];
$access_token = $accessTokenStore->get();

$wargamingClient = new \hwao\WargamingApi\Client\WargamingClient($application_id, $log);

$prolongTokenH = godzina(17, 30);
if ($now == $prolongTokenH) {
	$log->info(sprintf('Wydluzenie tokena %d:%d', intdiv($prolongTokenH, 60), $prolongTokenH % 60));

	$authenticationClient = new \hwao\WargamingApi\Client\AuthenticationClient($wargamingClient);
	$access_token = $authenticationClient->prolongate($access_token, strtotime('+7 days'));
	$accessTokenStore->set($access_token);
	die();
}

$strongholdClient = new \hwao\WargamingApi\Client\StrongholdClient($wargamingClient);

$reserveSchedule = \hwao\WargamingClanBot\ReserveScheduleBuilder::buildSchedule(new DateTimeImmutable());
$powinnaByc = $reserveSchedule->getPierwsza();

// Godzina bez rezerw
if (empty($powinnaByc)) {
	$log->info('Brak rezerwy w harmonogramie - okienko zeby puscic rezerwy na hajs zgodnie z planem');
	die();
} else {
	$log->debug('Harmonogram wyznaczył: ' . $powinnaByc);
}


$uruchomioneRezerwy = $strongholdClient->clanReserves($access_token);

$odpalZa = zaIleSekundOdpal($uruchomioneRezerwy[$powinnaByc]);

if ($odpalZa >= 55) {
	$log->info('Rezerwa ' . $powinnaByc . ' dziala jeszcze ponad 55s (' . $odpalZa . ') do: ' . date('H:m:s', time() + $odpalZa));
	die();
}

// Ponizej 55s
// Usupiam do czasu konca rezerwy i odpalam
$uspij = $odpalZa + 2;

$log->info('Sleep na ' . $uspij . ' (' . $odpalZa . 's + 2s)');
sleep($uspij);
$log->info('Wstal');

// Sterowane jest 1wsza, wiec odpalam 2ga zeby zawsze sie konczyla przed
$drugaRezerwa = $reserveSchedule->getDruga();
$log->info('Odpalam druga rezerwe: ' . $drugaRezerwa);
$response1 = $strongholdClient->activateAvailableClanReserve($access_token, 10, $drugaRezerwa);

sleep(1);

$log->info('Odpalam pierwsza rezerwe: ' . $powinnaByc);
$response2 = $strongholdClient->activateAvailableClanReserve($access_token, 10, $powinnaByc);




