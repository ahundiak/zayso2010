<table border="1">
    <tr><th colspan="4">People Associated With This Account</th></tr>
    <tr>
        <th>Edit</th>
        <th>Relation</th>
        <th>Name</th>
        <th>Referee Info</th>
    </tr>
    <?php foreach $accountPersons as $accountPerson) { ?>
    <tr>
        <td><a href="<?php echo $view['router']->generate('ayso_area_account_person_edit',array('id' => $accountPerson->getId())); ?>">
               Edit</a></td>
        <td><?php echo $view->escape($accountPerson->getAccountRelation()); ?></td>
        <td><?php echo $view->escape($accountPerson->getPersonName()); ?></td>
        <td><?php echo $view->escape($accountPerson->getRefBadge()); ?></td>
    </tr>
    <?php } ?>
    <tr><th colspan="4">
            <a href="<?php echo $view['router']->generate('ayso_area_account_person_add'); ?>">
               Add Person to Account</a>
        </th>
    </tr>
</table>

