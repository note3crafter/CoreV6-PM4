<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\tile;

use pocketmine\block\Block;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\tile\Spawnable;
use TheNote\core\server\NoteBlockSound;

class NoteBlock extends Spawnable {

    public const TAG_POWERED = "note";
    public const TAG_NOTE = "powered";
    protected $note = 0;
    protected $powered = false;

    protected function readSaveData(CompoundTag $nbt) : void{
        $this->note = max(0, min(24, $nbt->getByte(self::TAG_NOTE, 0, true)));
        $this->powered = boolval($nbt->getByte(self::TAG_POWERED, 0));
    }

    public function setNote(int $note) : void{
        $this->note = $note;
    }

    public function getPitch() : int{
        return $this->note;
    }

    public function addPitch() : void{
        $this->note = ($this->note + 1) % 25;
    }

    public function getSound() : int {
        $up = $this->level->getBlock($this->getSide(Vector3::SIDE_UP));
        if($up->getId() === Block::AIR){
            $below = $this->level->getBlock($this->getSide(Vector3::SIDE_DOWN));
            $instrument = NoteBlockSound::INSTRUMENT_PIANO;

            switch($below->getId()){ // TODO: implement block materials
                //Bass
                case ItemIds::WOOD:
                case ItemIds::PLANKS:
                case ItemIds::WOODEN_SLAB:
                case ItemIds::DOUBLE_WOODEN_SLAB:
                case ItemIds::OAK_STAIRS:
                case ItemIds::SPRUCE_STAIRS:
                case ItemIds::BIRCH_STAIRS:
                case ItemIds::JUNGLE_STAIRS:
                case ItemIds::ACACIA_STAIRS:
                case ItemIds::DARK_OAK_STAIRS:
                case ItemIds::FENCE:
                case ItemIds::FENCE_GATE:
                case ItemIds::SPRUCE_FENCE_GATE:
                case ItemIds::BIRCH_FENCE_GATE:
                case ItemIds::JUNGLE_FENCE_GATE:
                case ItemIds::DARK_OAK_FENCE_GATE:
                case ItemIds::ACACIA_FENCE_GATE:
                case ItemIds::BOOKSHELF:
                case ItemIds::CHEST:
                case ItemIds::CRAFTING_TABLE:
                case ItemIds::SIGN_POST:
                case ItemIds::WALL_SIGN:
                case ItemIds::OAK_DOOR_BLOCK:
                case ItemIds::SPRUCE_DOOR_BLOCK:
                case ItemIds::BIRCH_DOOR_BLOCK:
                case ItemIds::JUNGLE_DOOR_BLOCK:
                case ItemIds::ACACIA_DOOR_BLOCK:
                case ItemIds::DARK_OAK_DOOR_BLOCK:
                case ItemIds::NOTEBLOCK:
                    $instrument = NoteBlockSound::INSTRUMENT_BASS;
                    break;
                //Snare Drum
                case ItemIds::SAND:
                case
                $instrument = NoteBlockSound::INSTRUMENT_TABOUR;
                    break;
                //Clicks and Sticks
                case ItemIds::GLASS:
                case ItemIds::GLASS_PANE:
                case ItemIds::SEA_LANTERN;
                    $instrument = NoteBlockSound::INSTRUMENT_CLICK;
                    break;
                //Bass Drum
                case ItemIds::STONE:
                case ItemIds::COBBLESTONE:
                case ItemIds::SANDSTONE:
                case ItemIds::MOSS_STONE:
                case ItemIds::BRICK_BLOCK:
                case ItemIds::STONE_BRICK:
                case ItemIds::NETHER_BRICK_BLOCK:
                case ItemIds::QUARTZ_BLOCK:
                case ItemIds::STONE_SLAB:
                case ItemIds::COBBLESTONE_STAIRS:
                case ItemIds::BRICK_STAIRS:
                case ItemIds::STONE_BRICK_STAIRS:
                case ItemIds::NETHER_BRICK_STAIRS:
                case ItemIds::SANDSTONE_STAIRS:
                case ItemIds::QUARTZ_STAIRS:
                case ItemIds::COBBLESTONE_WALL:
                case ItemIds::NETHER_BRICK_FENCE:
                case ItemIds::BEDROCK:
                case ItemIds::GOLD_ORE:
                case ItemIds::IRON_ORE:
                case ItemIds::COAL_ORE:
                case ItemIds::LAPIS_ORE:
                case ItemIds::DIAMOND_ORE:
                case ItemIds::REDSTONE_ORE:
                case ItemIds::GLOWING_REDSTONE_ORE:
                case ItemIds::EMERALD_ORE:
                case ItemIds::FURNACE:
                case ItemIds::BURNING_FURNACE:
                case ItemIds::OBSIDIAN:
                case ItemIds::MONSTER_SPAWNER:
                case ItemIds::NETHERRACK:
                case ItemIds::ENCHANTING_TABLE:
                case ItemIds::END_STONE:
                case ItemIds::TERRACOTTA:
                case ItemIds::COAL_BLOCK:
                    $instrument = NoteBlockSound::INSTRUMENT_BASS_DRUM;
                    break;
                //Bells
                case ItemIds::GOLD_BLOCK;
                    $instrument = NoteBlockSound::INSTRUMENT_GLOCKENSPIEL;
                    break;
                //Cow Bell
                case ItemIds::SOUL_SAND;
                    $instrument = NoteBlockSound::INSTRUMENT_COW_BELL;
                    break;
                //Banjo
                case ItemIds::HAY_BALE;
                    $instrument = NoteBlockSound::INSTRUMENT_BANJO;
                    break;
                //Pling
                case ItemIds::GLOWSTONE;
                    $instrument = NoteBlockSound::INSTRUMENT_ELECTRIC_PIANO;
                    break;
                //Bit
                case ItemIds::EMERALD_BLOCK;
                    $instrument = NoteBlockSound::INSTRUMENT_SQUARE_WAVE;
                    break;
                //Didgeridoo
                case ItemIds::PUMPKIN;
                    $instrument = NoteBlockSound::INSTRUMENT_DIDGERIDOO;
                    break;
                //Iron Xylophone
                case ItemIds::IRON_BLOCK;
                    $instrument = NoteBlockSound::INSTRUMENT_VIBRAPHONE;
                    break;
                //Guitar
                case ItemIds::WOOL;
                    $instrument = NoteBlockSound::INSTRUMENT_GUITAR;
                    break;
                //Xylophone
                case ItemIds::BONE_BLOCK;
                    $instrument = NoteBlockSound::INSTRUMENT_XYLOPHONE;
                    break;
                //Chimes
                case ItemIds::PACKED_ICE;
                case ItemIds::BLUE_ICE;
                    $instrument = NoteBlockSound::INSTRUMENT_CHIME;
                    break;
                //Flute
                case ItemIds::CLAY_BLOCK;
                case ItemIds::SLIME_BLOCK;
                    //case Block::Honeycomb; //Future
                    $instrument = NoteBlockSound::INSTRUMENT_FLUTE;
                    break;


            }
            $this->level->addSound(new NoteBlockSound($this, $instrument, $this->note));

            return true;
        }
        return false;
    }


    public function setPowered(bool $value) : void{
        $this->powered = $value;
    }

    public function isPowered() : bool{
        return $this->powered;
    }

    public function getDefaultName() : string{
        return "NoteBlock";
    }

    protected function writeSaveData(CompoundTag $nbt) : void{
        $nbt->setByte(self::TAG_NOTE, $this->note, true);
        $nbt->setByte(self::TAG_POWERED, intval($this->powered));
    }

    public function addAdditionalSpawnData(CompoundTag $nbt) : void{

    }
}