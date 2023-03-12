<?php if (isset($menu_settings)): ?>
    <section class="main-menu">
        <h1>Vitalijaus bankas</h1>
        <div class="main-menu-links"> 
            <a <?= $menu_settings['here'] === 1 ? 'class="here-rn"' : '' ?> href="http://localhost/manophp/bank_v1/pages/sarasas.php">Klientų sąrašas</a>
            <a <?= $menu_settings['here'] === 2 ? 'class="here-rn"' : '' ?> href="http://localhost/manophp/bank_v1/pages/naujas.php">Naujas klientas</a>
            <?php if ($menu_settings['edit'] === true): ?>
                <a <?= $menu_settings['here'] === 3 ? 'class="here-rn"' : '' ?> href="http://localhost/manophp/bank_v1/pages/prideti.php?id=<?= $id ?>">Pridėti lėšų</a>
                <a <?= $menu_settings['here'] === 4 ? 'class="here-rn"' : '' ?> href="http://localhost/manophp/bank_v1/pages/nuskaiciuoti.php?id=<?= $id ?>">Nuskaičiuoti lėšas</a>
            <?php endif ?>
        </div>
    </section>
<?php endif ?>