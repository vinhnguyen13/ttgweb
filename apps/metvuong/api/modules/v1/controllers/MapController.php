<?php
namespace api\modules\v1\controllers;

use yii\rest\Controller;
use frontend\models\MapSearch;
use yii\db\Query;
use frontend\models\frontend\models;
use frontend\models\Elastic;
use vsoft\ad\models\AdProduct;

class MapController extends Controller {
	public function actionGet() {
		$v = Elastic::transform(\Yii::$app->request->get('v', ''));
		
		$slop = 8;
		
		$should = [
			[
				"match_phrase_prefix" => [
					"search_field" => [
						"query" => $v,
						"slop" => $slop
					]
				]	
			]
		];
		
		$functions = [
			[
				"filter" => [
					"match_phrase" => [
						"search_name" => $v
					]
				],
				"weight" => 1
			],
			[
				"filter" => [
					"match_phrase_prefix" => [
						"search_name" => $v
					]
				],
				"weight" => 1
			],
			[
				"filter" => [
					"match_phrase_prefix" => [
						"search_field" => $v
					]
				],
				"weight" => 1
			],
			[
				"filter" => [
					"match_phrase_prefix" => [
						"search_field" => [
							"query" => $v,
							"slop" => $slop
						]
					]
				],
				"weight" => 3
			],
			[
				"filter" => [
					"match" => [
						"_type" => "city"
					]
				],
				"weight" => 3
			],
			[
				"filter" => [
					"match" => [
						"_type" => "district"
					]
				],
				"weight" => 2
			],
			[
				"filter" => [
					"match" => [
						"city_id" => 1
					]
				],
				"weight" => 2
			],
		];
		
		$additionSearch = preg_replace("/(q|quan|p|phuong)([0-9])/", "$1 $2", $v);
		$additionSearch = preg_replace("/so\s?(?=[0-9])/", "$1", $additionSearch);
		
		if(strpos($additionSearch, "du an") !== false) {
			$additionSearch = str_replace("du an", "", $v);
		
			$functions[] = [
				"filter" => [
					"match" => [
						"_type" => "project_building"
					]
				],
				"weight" => 4
			];
		}
		
		if($additionSearch != $v) {
			$should[] = [
				"match_phrase_prefix" => [
					"search_field" => [
						"query" => $additionSearch,
						"slop" => $slop
					]
				]
			];
			
			$functions[] = [
				"filter" => [
					"match_phrase_prefix" => [
						"search_field" => [
							"query" => $additionSearch,
							"slop" => $slop
						]
					]
				],
				"weight" => 3
			];
		}
		
		$correctText = $this->correctText($additionSearch);
		
		if($correctText != $additionSearch) {
			$should[] = [
				"match_phrase_prefix" => [
					"search_field" => [
						"query" => $correctText,
						"slop" => $slop
					]
				]
			];
			
			$functions[] = [
				"filter" => [
					"match_phrase_prefix" => [
						"search_field" => [
							"query" => $correctText,
							"slop" => $slop
						]
					]
				],
				"weight" => 3
			];
		}
		
		$params = [
			"query" => [
				"function_score" => [
					"query" => [
						"bool" => [
							"should" => $should
						]
					],
					"functions" => $functions,
					"boost_mode" => "replace",
					"score_mode" => "sum"
				]
			]
		];
		
		$response = [];
		 
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . '/' . \Yii::$app->params['indexName']['countTotal'] . '/_search');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 
		$result = json_decode(curl_exec($ch), true);
		
		foreach ($result['hits']['hits'] as $k => $hit) {
			$response[$k]['full_name'] = $hit['_source']['full_name'];
			$response[$k]['type'] = $hit['_type'];
			$response[$k]['id'] = $hit['_id'];
		}
		
		return $response;
		
		exit();
		
		$v = \Yii::$app->request->get('v');
		$t = \Yii::$app->request->get('t', AdProduct::TYPE_FOR_SELL);
		
    	$v = Elastic::transform($v);
    	
    	$sentence = explode(' ', $v);
    	$firstWord = $sentence[0];
    	
    	$boost = [
			[
				"match" => [
					"city_id" => [
						"query" => 1,
						"boost" => 1
					]
				]
			],
			[
				"span_first" => [
					"match" => [
						"span_term" => [
							"search_name" => $firstWord
						]
					],
					"end" => 1,
					"boost" => 3
				]
			]
    	];

    	$additionSearch = preg_replace("/(q|quan|p|phuong|cmt)([0-9])/", "$1 $2", $v);
    	$additionSearch = preg_replace("/(?<=[0-9])\s?(thang)\s?(?=[0-9])/", "/", $additionSearch);
    	
    	if(strpos($additionSearch, "du an") !== false) {
    		$additionSearch = str_replace("du an", "", $v);
    		
    		$boost[] = [
				"match" => [
					"_type" => [
						"query" => "project_building",
						"boost" => 20000	
					]	
				]
			];
    	} else {
    		$boost[] = [
				"match" => [
					"_type" => [
						"query" => "city",
						"boost" => 1	
					]	
				],
				"match" => [
					"_type" => [
						"query" => "district",
						"boost" => 0.8	
					]	
				]
			];
    	}
    	
    	$search = [
			[
				"match" => [
					"search_name" => [
						"query" => $v,
						"operator" => "and"	
					]
				]
			],
			[
				"match_phrase_prefix" => [
					"search_name" => [
						"query" => $v,
						"operator" => "and",
						"slop" => 5
					]
				]
			],
			[
				"match_phrase_prefix" => [
					"search_field" => [
						"query" => $v,
						"operator" => "and",
						"slop" => 8
					]
				]
			]
    	];
    	
    	if($additionSearch != $v) {
    		if($additionSearch == '') {
    			$search[] = [
    					"match" => [
    							"_type" => [
    									"query" => "project_building"
    							]
    					]
    			];
    		} else {
    			$search[] = [
    					"match" => [
    							"search_name" => [
    									"query" => $additionSearch,
    									"operator" => "and"
    							]
    					]
    			];
    			$search[] = [
    					"match_phrase_prefix" => [
    							"search_name" => [
    									"query" => $additionSearch,
    									"operator" => "and",
    									"slop" => 5
    							]
    					]
    			];
    			$search[] = [
    					"match_phrase_prefix" => [
    							"search_field" => [
    									"query" => $additionSearch,
    									"operator" => "and",
    									"slop" => 8
    							]
    					]
    			];
    		}
    	}
    	
    	$consonantArr = ["ngh", "ph", "th", "tr", "gi", "ch", "nh", "ng", "kh", "gh"];
    	
    	$consonantLv = [
			1 => "c|q|k|t|r|h|b|m|v|n|l|x|p|s|d|g",
			2 => "ph|th|tr|gi|ch|nh|ng|kh|gh",
			3 => "ngh"
    	];
    	
    	$consonant = "(" . $consonantLv[3] . "|" . $consonantLv[2] . "|" . $consonantLv[1] . ")";
    	$vowel = "(a|o|y|e|u|i)";
    	
    	$correctText = preg_replace_callback("/$consonant(?=$consonant)/", function($matches) use ($consonantArr) {
    		if(in_array($matches[0].$matches[2], $consonantArr)) {
    			return $matches[0];
    		} else {
    			return $matches[0] . " ";
    		}
    	}, $additionSearch);
    	
		$correctText = preg_replace_callback("/(?<=$vowel)$consonant(?=$vowel)/", function($matches) {
			return " " . $matches[0];
		}, $correctText);
    	
    	if($correctText != $additionSearch) {
    		$search[] = [
				"match" => [
					"search_name" => [
						"query" => $correctText,
						"operator" => "and",
						"boost" => -0.09
					]
				]
			];
    		$search[] = [
				"match_phrase_prefix" => [
					"search_name" => [
						"query" => $correctText,
						"operator" => "and",
						"slop" => 5,
						"boost" => -0.09
					]
				]
			];
    		$search[] = [
				"match_phrase_prefix" => [
					"search_field" => [
						"query" => $correctText,
						"operator" => "and",
						"slop" => 8,
						"boost" => -0.09
					]
				]
			];
    	}
    	
    	$params = [
    		"query" => [
    			"bool" => [
    				"must" => [
    					"bool" => [
    						"should" => $search
    					]
    				],
    				"should" => $boost
    			]	
    		],
    		'_source' => ['full_name', AdProduct::TYPE_FOR_SELL_TOTAL, AdProduct::TYPE_FOR_RENT_TOTAL]
    	];
    	
    	$response = [];
    	
    	$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . '/' . \Yii::$app->params['indexName']['countTotal'] . '/_search');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		 
		$result = json_decode(curl_exec($ch), true);

		foreach ($result['hits']['hits'] as $k => $hit) {
			$response[$k]['full_name'] = $hit['_source']['full_name'];
			$response[$k]['type'] = $hit['_type'];
			$response[$k]['id'] = $hit['_id'];
		}
		
		return $response;
	}
	
	function correctText($s) {
		$consonantArr = ["ngh", "ph", "th", "tr", "gi", "ch", "nh", "ng", "kh", "gh"];
		 
		$consonantLv = [
			1 => "c|q|k|t|r|h|b|m|v|n|l|x|p|s|d|g",
			2 => "ph|th|tr|gi|ch|nh|ng|kh|gh",
			3 => "ngh"
		];
		 
		$consonant = "(" . $consonantLv[3] . "|" . $consonantLv[2] . "|" . $consonantLv[1] . ")";
		$vowel = "(a|o|y|e|u|i)";
		 
		$correctText = preg_replace_callback("/$consonant(?=$consonant)/", function($matches) use ($consonantArr) {
			if(in_array($matches[0].$matches[2], $consonantArr)) {
				return $matches[0];
			} else {
				return $matches[0] . " ";
			}
		}, $s);
			 
		$correctText = preg_replace_callback("/(?<=$vowel)$consonant(?=$vowel)/", function($matches) {
			return " " . $matches[0];
		}, $correctText);
		
		return $correctText;
	}
}