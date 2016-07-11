<?php include('header.php') ?>
<?php
if ( $games && count($games) > 0 ) {
?>
    <table cellspacing="0" cellpadding="0">
        <tr>
            <th class="name">Games we want</th>
            <th class="votes">Votes</th>
            <th class="action">&nbsp;</th>
        </tr>
        <?php
        foreach ( $games as $game ) {
        ?>
            <tr>
                <td class="name"><?= $game->title ?></td>
                <td class="votes"><?= $game->votes ?></td>
                <td class="action">
                    <a href="?action=gotit&amp;id=<?= $game->id ?>">Got it</a>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
<?php
} else {
?>
    <p>There are no games currently listed.</p>
<?php
}
?>
<?php include('footer.php') ?>