<?php
class SynchronizableHelpContent extends SynchronizableObject
{
    public static function findAll($local, $since = 0, $version = null)
    {
        if ($local === null) {
            $query = 'studip_version = :studip_version AND chdate > :chdate';
        } elseif ($local) {
            $query = 'studip_version = :studip_version AND chdate > :chdate AND installation_id = :installation_id';
        } else {
            $query = 'studip_version = :studip_version AND chdate > :chdate AND installation_id = :installation_id';
        }

        $items = HelpContent::findBySQL($query, array(
            ':studip_version'  => $version ?: Studip::version(),
            ':chdate'          => $since,
            ':installation_id' => Studip::id(),
        ));

        $result = array();
        foreach ($items as $item) {
            $result[] = new self($item);
        }
        return $result;
    }

    public static function getLatestStudipVersion($language = null)
    {
        $query = "SELECT MAX(`studip_version`)
                  FROM `help_content`
                  WHERE `language` = IFNULL(:language, `language`)";
        $statement = DBManager::get()->prepare($query);
        $statement->bindValue(':language', $language);
        $statement->execute();

        return $statement->fetchColumn() ?: false;
    }

    protected $sorm;

    public function __construct($id = null)
    {
        if (is_object($id) && $id instanceof HelpContent) {
            $this->sorm = $id;
        } else {
            $this->sorm = new HelpContent($id);
        }
    }

    public function replace(SynchronizableObject $new_object)
    {

    }

    public function updateInfo($version, $installation_id)
    {
        $this->sorm->studip_version  = $version;
        $this->sorm->installation_id = $installation_id;

        if ($this->sorm->isDirty()) {
            $this->sorm->setNew(true);
        }
    }

    public function store()
    {
        $this->sorm->store();
    }

    public function remove()
    {
        if ($this->installation_id === Studip::id()) {
            throw new Exception('You may not remove a local item');
        }
        $this->sorm->delete();
    }

    public function getTitle()
    {
        return sprintf(_('Hilfelaschen-Text für Seite "%s" [%s]'), $this->sorm->route, $this->sorm->language);
    }

    public function getValue($key)
    {
    }

    public function setValue($key, $value)
    {
    }

    public function toArray()
    {
    }

    public function __clone()
    {
        $this->sorm = clone $this->sorm;
    }
}