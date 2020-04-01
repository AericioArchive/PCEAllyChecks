<?php

declare(strict_types=1);

namespace Aericio\PCEAllyChecks;

use Chalapa13\WorldGuard\Region;
use Chalapa13\WorldGuard\WorldGuard;
use DaPigGuy\PiggyCustomEnchants\utils\AllyChecks;
use factions\FactionsPE;
use factions\manager\Members;
use factions\relation\Relation;
use FactionsPro\FactionMain;
use pocketmine\entity\Entity;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use slapper\entities\SlapperEntity;
use slapper\entities\SlapperHuman;
use slapper\Main as SlapperMain;

/**
 * Class PCEAllyChecks
 * @package Aericio\PCEAllyChecks
 */
class PCEAllyChecks extends PluginBase
{
    public function onEnable(): void
    {
        if (!is_null($this->getFactionsPro())) {
            AllyChecks::addCheck($this, function (Player $player, Entity $entity): bool {
                $pl = $this->getFactionsPro();
                if ($entity instanceof Player) {
                    $a = $player->getName();
                    $b = $entity->getName();
                    if ($pl->isInFaction($a) && $pl->isInFaction($b)) {
                        if ($pl->sameFaction($a, $b) || $pl->areAllies($pl->getFaction($a), $pl->getFaction($b))) return true;
                    }
                }
                return false;
            });
        }
        if (!is_null($this->getFactionsPE())) {
            AllyChecks::addCheck($this, function (Player $player, Entity $entity): bool {
                if ($entity instanceof Player) {
                    $a = Members::get($player);
                    $b = Members::get($entity);
                    if (Relation::sameFaction($a, $b) || Relation::isAlly($a, $b)) return true;
                }
                return false;
            });
        }
        if (!is_null($this->getWorldGuard())) {
            AllyChecks::addCheck($this, function (Player $player, Entity $entity): bool {
                $pl = $this->getWorldGuard();
                $reg = $pl->getRegion($pl->getRegionOf($player));
                if ($reg instanceof Region) {
                    if ($reg->getFlag("pvp") === "false") return true;
                }
                return false;
            });
        }
        if (!is_null($this->getSlapper())) {
            AllyChecks::addCheck($this, function (Player $player, Entity $entity): bool {
                if ($entity instanceof SlapperEntity || $entity instanceof SlapperHuman) return true;
                return false;
            });
        }
    }

    /**
     * @return FactionMain|null
     */
    public function getFactionsPro(): ?FactionMain
    {
        $pl = $this->getServer()->getPluginManager()->getPlugin('FactionsPro');
        if ($pl instanceof FactionMain) return $pl;
        return null;
    }

    /**
     * @return FactionsPE|null
     */
    public function getFactionsPE(): ?FactionsPE
    {
        $pl = $this->getServer()->getPluginManager()->getPlugin('FactionsPE');
        if ($pl instanceof FactionsPE) return $pl;
        return null;
    }

    /**
     * @return SlapperMain|null
     */
    public function getSlapper(): ?SlapperMain
    {
        $pl = $this->getServer()->getPluginManager()->getPlugin('Slapper');
        if ($pl instanceof SlapperMain) return $pl;
        return null;
    }

    /**
     * @return WorldGuard|null
     */
    public function getWorldGuard(): ?WorldGuard
    {
        $pl = $this->getServer()->getPluginManager()->getPlugin('WorldGuard');
        if ($pl instanceof WorldGuard) return $pl;
        return null;
    }
}