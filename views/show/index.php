<? if (empty($objects)): ?>
    <?= MessageBox::info(_('Es liegen keine Inhalte f�r Ihre aktuelle Stud.IP-Version vor.')) ?>
    <? if ($latest_version): ?>
    <p>
        <?= sprintf(_('Die aktuellsten Inhalte sind f�r Stud.IP Version %s.'), $latest_version) ?><br>
        <?= _('Klicken Sie auf den folgenden Button, um die Inhalte f�r die aktuelle Version zu aktualisieren.') ?><br>
        <?= Studip\LinkButton::create(_('Inhalte kopieren'), $controller->url_for('show/copy?version=' . $latest_version)) ?>
    </p>
    <? endif; ?>
<? endif; ?>

<ul>
<? foreach ($objects as $object): ?>
    <li>
        <?= $object->getTitle() ?>
    </li>
<? endforeach; ?>
</ul>