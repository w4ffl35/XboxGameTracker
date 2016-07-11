<?php include('header.php') ?>
<?php
if ( $games && count($games) > 0 ) {
?>
    <table cellspacing="0" cellpadding="0">
        <tr>
            <th class="name">Games we own</th>
        </tr>
        <?php
        foreach ( $games as $game ) {
        ?>
            <tr>
                <td class="name"><?= $game->title ?></td>
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