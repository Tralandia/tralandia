<?php

namespace Horo;


Sanity::init();

class Sanity {

	private static $__instance = NULL;
	
	public static function init() {

		if( self::$__instance == NULL ) {
			self::$__instance = new Sanity();
		}

		return self::$__instance;
	}		

	public function doEncoding($encodeType = FALSE, $encodeContent = FALSE) {
		$encoded = $encodeContent;

		if($encodeType && $encodeContent) {
			switch ($encodeType) {
				case '0':
					#7bit
					$encoded = imap_8bit($encodeContent);
					break;

				case '1':
					#8bit
					$encoded = imap_8bit($encodeContent);
					break;

				case '2':
					#binary
					$encoded = imap_binary($encodeContent);
					break;

				case '3':
					#base64
					$encoded = imap_base64($encodeContent);

					break;

				case '4':
					#QUOTED-PRINTABLE
					$encoded = imap_qprint($encodeContent);

					break;

				case '5':
					#other
					$encoded = imap_base64($encodeContent);

					break;

			}
		}

		return $encoded;
	}


	public function iconvDecode($from = FALSE, $string = FALSE) {
			$decoded = $string;

			$utf = new utf8();

			$decoded = $utf->convert($string, $from);

			$decoded = self::removeBom($decoded);

			#$decoded = quoted_printable_decode ($decoded);
			#$decoded = mb_convert_encoding($decoded, "UTF-8");
			return $decoded;
/*
			$decoded = utf8_encode($decoded);
			$decoded = iconv(strtoupper($from), 'UTF-8//TRANSLIT', $decoded);
			#$decoded = iconv(strtoupper($from), 'ASCII//TRANSLIT', $decoded);
			return $decoded;
*/
	}

	public static function removeBom($str = false) {
		if($str) {
			if(substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf)) {
					$str=substr($str, 3);
			}
		}

		return $str;
	}

	public function asciiTranslate($string = '') {
		return iconv(strtoupper($from), 'ASCII//TRANSLIT', $decoded);
	}

	public function mimeDecode($string) {
		return trim(iconv_mime_decode($string, 0, 'UTF-8'));
	}

}
