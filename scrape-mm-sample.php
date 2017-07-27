<?php 

// get html using curl
function curlGet($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_URL, $url);
    $html = curl_exec($ch);
    curl_close($ch);    

    return $html;
}

// ngambil harga yg berupa angka aja
function priceParse($src) {
  return preg_replace('/[^0-9]/', '', $src);
}

function getDataMatahariMall($kw) {
  $html = curlGet('http://www.mataharimall.com/products?per_page=36&query='. urlencode($kw));

  // load html 
  $dom = new DomDocument();
  @$dom->loadHTML($html);
  $xpath = new DOMXPATH($dom);

  // xpath query to put the values
  $mtmall_brand = 'MatahariMall.com';
  $mtmall_name = $xpath->query('//div[@class="c-card-product"]/a/@title');
  $mtmall_price = $xpath->query('//div[@class="c-product__discount-price"]');
  $mtmall_url = $xpath->query('//div[@class="c-card-product"]/a/@href');
  $mtmall_thumb = $xpath->query('//div[@class="c-card-product__image c-card-product-hover"]/figure/img/@data-src');
  $len = $mtmall_name->length;

  // masukkin hasil query xpath ke array
  $data = [];
  for ($i = 0; $i < $len; $i++){
    $data[] = [
          'productName' => $mtmall_name->item($i)->nodeValue,
          'productPrice' => (int) priceParse($mtmall_price->item($i)->nodeValue),
          'productUrl' => 'http://www.mataharimall.com/' . (string) $mtmall_url->item($i)->nodeValue,
          'productThumb' => $mtmall_thumb->item($i)->nodeValue,
          'productSrc' => $mtmall_brand
        ];
  }
  return $data;
}


if ((isset($_GET['q'])) && ($_GET['q'] != null)) {
  $keyword = $_GET['q'];
  $datamm = getDataMatahariMall($keyword);
  foreach ($datamm as $mm) {
    echo '<a href="' . $mm['productUrl'] . '">' . $mm['productName'] . '</a>, Rp '
     . number_format($mm['productPrice'], 0, ',', '.') . ' from ' . $mm['productSrc'] . '</br>';
  }
} else {
    echo '<form action="" method="get">
            <input type="search" name="q">
          </form>';
}

?>

