<?php
namespace AYSO\Team;

/**
 * @Entity
 * @Table(schema="ayso", name="ayso.team_player")
 */
class TeamPlayerItem
{
  /**
   * @Id
   * @Column(type="integer")
   * @GeneratedValue(strategy="AUTO")
   */
  private $id;

 
  /**
   * @ManyToOne(targetEntity="AYSO\Team\TeamItem", inversedBy="players")
   * @JoinColumn(name="team_id", referencedColumnName="id")
   */
  private $team;

  /** --------------------------------
   * @Column(type="string",length="20",name="team_id")
   */
  private $teamId;

  /**
   * @ManyToOne(targetEntity="AYSO\Player\PlayerItem", inversedBy="teams")
   * @JoinColumn(name="player_id", referencedColumnName="id")
   */
  private $player;

  /** --------------------------------
   * @Column(type="string",length="20",name="player_id")
   */
  private $playerId;

  /**
   * @Column(type="integer",name="jersey_number")
   */
  private $jerseyNumber = -1;

  public function getId()           { return $this->id; }
  public function getJerseyNumber() { return $this->jerseyNumber; }

  public function setJerseyNumber($value) { $this->jerseyNumber = $value; }

  public function getTeam()   { return $this->team; }
  public function getPlayer() { return $this->player; }

  public function setTeam($item)   { $this->team   = $item; }
  public function setPlayer($item) { $this->player = $item; }

  public function getTeamId()   { return $this->teamId;   }
  public function getPlayerId() { return $this->playerId; }
}
?>
