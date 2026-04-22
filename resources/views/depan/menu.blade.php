<?php 
foreach ($menu->get() as $mn) {
    $data[$mn->id_menuutama][] = $mn;
}
$menu = App\Helpers\Gudangfungsi::getMenu($data);

echo $menu;

// $cek = App\Helpers\Gudangfungsi::cek();
// echo "Cek: ".$cek;
?>