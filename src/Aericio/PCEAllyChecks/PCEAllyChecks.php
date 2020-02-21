<?php

declare(strict_types=1);

namespace Aericio\PCEAllyChecks;

use DaPigGuy\PiggyCustomEnchants\utils\AllyChecks;
use factions\FactionsPE;
use factions\manager\Members;
use factions\relation\Relation;
use FactionsPro\FactionMain;
use pocketmine\entity\Entity;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

/**
 * Class PCEAllyChecks
 * @package Aericio\PCEAllyChecks
 */
class PCEAllyChecks extends PluginBase
{
    public function onEnable(): void
    {
        AllyChecks::addCheck($this, function (Player $player, Entity $entity): bool {
            if ($entity instanceof Player) {
                $f = $this->getFactionsPro();
                if (!is_null($f)) {
                    $a = $player->getName();
                    $b = $entity->getName();
                    if ($f->isInFaction($a) && $f->isInFaction($b)) {
                        if ($f->sameFaction($a, $b) || $f->areAllies($f->getFaction($a), $f->getFaction($b))) return true;
                    }
                }
                if (!is_null($this->getFactionsPE())) {
                    $a = Members::get($player);
                    $b = Members::get($entity);
                    if (Relation::sameFaction($a, $b) || Relation::isAlly($a, $b)) return true;
                }
            }
            return false;
        });
    }

    /**
     * @return FactionMain|null
     */
    public function getFactionsPro(): ?FactionMain
    {
        /* @var FactionMain $factionspro */
        $factionspro = $this->getServer()->getPluginManager()->getPlugin('FactionsPro');
        return $factionspro ?? null;
    }

    /**
     * @return FactionsPE|null
     */
    public function getFactionsPE(): ?FactionsPE
    {
        /* @var FactionsPE $factionspe */
        $factionspe = $this->getServer()->getPluginManager()->getPlugin('FactionsPE');
        return $factionspe ?? null;
    }
}