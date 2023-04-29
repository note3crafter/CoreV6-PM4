<?php

namespace TheNote\core\utils;

use pocketmine\block\Block;

class ChestShopDataManager
{
    private $database;

    public function __construct(string $path)
    {
        $this->database = new \SQLite3($path);
        $sql = "CREATE TABLE IF NOT EXISTS Main(
					id INTEGER PRIMARY KEY AUTOINCREMENT,
					shopOwner TEXT NOT NULL,
					saleNum INTEGER NOT NULL,
					price INTEGER NOT NULL,
					productID INTEGER NOT NULL,
					productMeta INTEGER NOT NULL,
					signX INTEGER NOT NULL,
					signY INTEGER NOT NULL,
					signZ INTEGER NOT NULL,
					chestX INTEGER NOT NULL,
					chestY INTEGER NOT NULL,
					chestZ INTEGER NOT NULL
		)";
        $this->database->exec($sql);
    }

    public function registerShop(string $shopOwner, int $saleNum, int $price, int $productID, int $productMeta, Block $sign, Block $chest) : bool
    {
        $x = $sign->getPosition()->getFloorX();
        $y = $sign->getPosition()->getFloorY();
        $z = $sign->getPosition()->getFloorZ();
        $cx = $chest->getPosition()->getFloorX();
        $cy = $chest->getPosition()->getFloorY();
        $cz = $chest->getPosition()->getFloorZ();
        return $this->database->exec("INSERT OR REPLACE INTO Main (id, shopOwner, saleNum, price, productID, productMeta, signX, signY, signZ, chestX, chestY, chestZ) VALUES
			((SELECT id FROM Main WHERE signX = $x AND signY = $y AND signZ = $z),
			'$shopOwner', $saleNum, $price, $productID, $productMeta, $x, $y, $z, $cx, $cy, $cz)");
    }

    public function selectByCondition(array $condition) : bool|\SQLite3Result
    {
        $where = $this->formatCondition($condition);
        $res = false;
        try{
            $res = $this->database->query("SELECT * FROM Main WHERE $where");
        }finally{
            return $res;
        }
    }

    public function deleteByCondition(array $condition) : bool
    {
        $where = $this->formatCondition($condition);
        return $this->database->exec("DELETE FROM Main WHERE $where");
    }

    private function formatCondition(array $condition) : string
    {
        $result = "";
        $first = true;
        foreach ($condition as $key => $val) {
            if ($first) $first = false;
            else $result .= "AND ";
            $result .= "$key = $val ";
        }
        return trim($result);
    }
}