<?php

function triangular($x, $a, $b, $c) {
	if ($x <= $a || $x >= $c) return 0.0;
	if ($x == $b) return 1.0;
	if ($x > $a && $x < $b) return ($x - $a) / ($b - $a);
	return ($c - $x) / ($c - $b);
}

function extended_triangular($x, $a, $b, $c, $extend_left = false, $extend_right = false) {
	$normal = triangular($x, $a, $b, $c);
	if ($normal > 0) return $normal;
	
	if ($extend_left && $x < $a) {
		$distance = $a - $x;
		$range = $b - $a;
		if ($range > 0) {
			return max(0, 1 - ($distance / ($range * 2)));
		}
	}
	
	if ($extend_right && $x > $c) {
		$distance = $x - $c;
		$range = $c - $b;
		if ($range > 0) {
			return max(0, 1 - ($distance / ($range * 2)));
		}
	}
	
	return 0.0;
}

function temp_memberships($t){
	return [
		'dingin' => extended_triangular($t, 10, 18, 24, true, false),
		'sejuk'  => triangular($t, 20, 25, 30),
		'panas'  => extended_triangular($t, 28, 32, 37, false, true),
	];
}

function humidity_memberships($h){
	return [
		'kering' => extended_triangular($h, 20, 35, 50, true, false),
		'normal' => triangular($h, 45, 60, 75),
		'lembab' => extended_triangular($h, 70, 85, 95, false, true),
	];
}

function wind_memberships($w){
	return [
		'pelan'  => triangular($w, 0, 5, 12),
		'sedang' => triangular($w, 8, 15, 25),
		'kencang'=> extended_triangular($w, 20, 30, 45, false, true),
	];
}

function fuzzy_recommendation($t, $h, $w){
	$temp = temp_memberships($t);
	$hum  = humidity_memberships($h);
	$wind = wind_memberships($w);

	$rules = [

		['if'=>['sejuk','normal','pelan'],  'act'=>'Olahraga luar', 'weight'=>1.0],
		['if'=>['sejuk','normal','sedang'],  'act'=>'Jalan santai',  'weight'=>0.9],
		['if'=>['sejuk','lembab','pelan'],   'act'=>'Jalan santai teduh', 'weight'=>0.95],
		['if'=>['sejuk','lembab','sedang'],  'act'=>'Indoor ringan', 'weight'=>0.8],

		['if'=>['panas','lembab','pelan'],  'act'=>'Ngopi/Indoor AC', 'weight'=>0.9],
		['if'=>['panas','normal','sedang'],  'act'=>'Indoor ringan', 'weight'=>0.8],
		['if'=>['panas','lembab','sedang'],  'act'=>'Indoor saja',   'weight'=>0.95],

		['if'=>['dingin','normal','pelan'],  'act'=>'Olahraga ringan', 'weight'=>0.7],
		['if'=>['dingin','lembab','pelan'],  'act'=>'Di rumah hangat', 'weight'=>1.0],
		['if'=>['sejuk','normal','kencang'],'act'=>'Di rumah',        'weight'=>0.9],
		['if'=>['panas','lembab','kencang'],'act'=>'Di rumah',        'weight'=>1.0],
		['if'=>['sejuk','lembab','kencang'],'act'=>'Di rumah',        'weight'=>0.95],
		
		['if'=>['dingin','kering','pelan'],  'act'=>'Di rumah hangat', 'weight'=>0.8],
		['if'=>['dingin','kering','sedang'],  'act'=>'Di rumah', 'weight'=>0.9],
		['if'=>['dingin','kering','kencang'],  'act'=>'Di rumah', 'weight'=>1.0],
		['if'=>['dingin','normal','sedang'],  'act'=>'Indoor ringan', 'weight'=>0.7],
		['if'=>['dingin','normal','kencang'],  'act'=>'Di rumah', 'weight'=>0.95],
		['if'=>['dingin','lembab','sedang'],  'act'=>'Di rumah', 'weight'=>0.9],
		['if'=>['dingin','lembab','kencang'],  'act'=>'Di rumah', 'weight'=>1.0],
		
		['if'=>['panas','kering','pelan'],  'act'=>'Indoor AC/Sejuk', 'weight'=>0.9],
		['if'=>['panas','kering','sedang'],  'act'=>'Indoor saja', 'weight'=>0.95],
		['if'=>['panas','kering','kencang'],  'act'=>'Di rumah', 'weight'=>1.0],
		['if'=>['panas','normal','pelan'],  'act'=>'Indoor ringan', 'weight'=>0.85],
		['if'=>['panas','normal','kencang'],  'act'=>'Di rumah', 'weight'=>1.0],
		
		['if'=>['sejuk','kering','pelan'],  'act'=>'Olahraga luar', 'weight'=>0.9],
		['if'=>['sejuk','kering','sedang'],  'act'=>'Jalan santai', 'weight'=>0.85],
		['if'=>['sejuk','kering','kencang'],  'act'=>'Di rumah', 'weight'=>0.9],
	];

	$scores = [];
	foreach ($rules as $r){
		list($tK,$hK,$wK) = $r['if'];
		$alpha = min($temp[$tK] ?? 0, $hum[$hK] ?? 0, $wind[$wK] ?? 0) * ($r['weight'] ?? 1);
		if ($alpha <= 0) continue;
		$scores[$r['act']] = max($scores[$r['act']] ?? 0, $alpha);
	}

	if (!$scores){
		$domTemp = array_search(max($temp), $temp);
		$domHum = array_search(max($hum), $hum);
		$domWind = array_search(max($wind), $wind);
		
		$fallbackAction = 'Aktivitas fleksibel sesuai kondisi';
		$fallbackConfidence = 0.3;
		
		if ($domTemp && $domHum && $domWind) {
			if ($domTemp === 'panas' && max($temp) > 0.3) {
				$fallbackAction = 'Hindari aktivitas luar, pilih indoor';
				$fallbackConfidence = 0.4;
			} elseif ($domTemp === 'dingin' && max($temp) > 0.3) {
				$fallbackAction = 'Aktivitas indoor atau hangat';
				$fallbackConfidence = 0.4;
			} elseif ($domWind === 'kencang' && max($wind) > 0.3) {
				$fallbackAction = 'Hindari aktivitas luar, lebih aman di rumah';
				$fallbackConfidence = 0.4;
			} elseif ($domHum === 'lembab' && max($hum) > 0.3) {
				$fallbackAction = 'Aktivitas ringan atau indoor';
				$fallbackConfidence = 0.35;
			}
		}
		
		return ['action'=>$fallbackAction, 'confidence'=>$fallbackConfidence,
			'memberships'=>['temp'=>$temp,'humidity'=>$hum,'wind'=>$wind]];
	}

	arsort($scores);
	$bestAction = array_key_first($scores);
	$confidence = $scores[$bestAction];
	return ['action'=>$bestAction, 'confidence'=>$confidence,
		'memberships'=>['temp'=>$temp,'humidity'=>$hum,'wind'=>$wind]];
}
