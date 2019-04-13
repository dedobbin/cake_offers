<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Joboffer Entity
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $user_id
 * @property int $deleted
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\User $user
 */
class Joboffer extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'title' => true,
        'content' => true,
        'user_id' => true,
        'deleted' => true,
        'created' => true,
        'updated' => true,
        'user' => true
    ];
}
