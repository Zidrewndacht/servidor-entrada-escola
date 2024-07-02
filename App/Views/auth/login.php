<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.login') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>
<div  class="login-wrapper" >    
    <span class="login-title">IoT Entrada Escola</span>

<div class="modal-content" id="modal-login">
<div class="modal-status" >
    <h1><?= lang('Auth.login') ?></h1>
    <div id="session-info-field">
        <?php if (session('error') !== null) : ?>
            <div role="alert"><?= session('error') ?></div>
        <?php elseif (session('errors') !== null) : ?>
            <div id=validation-info" role="alert">
                <?php if (is_array(session('errors'))) : ?>
                    <?php foreach (session('errors') as $error) : ?>
                        <?= $error ?>
                        <br>
                    <?php endforeach ?>
                <?php else : ?>
                    <?= session('errors') ?>
                <?php endif ?>
            </div>
        <?php endif ?>
        <?php if (session('message') !== null) : ?>
        <div class="alert alert-success" role="alert"><?= session('message') ?></div>
        <?php endif ?>

    </div>
    </div>
    <form action="<?= url_to('login') ?>" method="post">
        <?= csrf_field() ?>

        <label for="email"><?= lang('Auth.email') ?></label>
        <input type="email" id="email" name="email" inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>" required>
        
        <label for="password"><?= lang('Auth.password') ?></label>
        <input type="password" id="password" name="password" inputmode="text" autocomplete="current-password" placeholder="<?= lang('Auth.password') ?>" required>

        <!-- Remember me -->
        <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                <label class="form-check-label" for="remember">  <?= lang('Auth.rememberMe') ?> </label>
                <input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')): ?> checked<?php endif ?>>
        <?php endif; ?>

        <input type="submit" class="btn btn-primary btn-block" value="<?= lang('Auth.login')?>">

        <!-- <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
            <p class="text-center"><?= lang('Auth.forgotPassword') ?> <a href="<?= url_to('magic-link') ?>"><?= lang('Auth.useMagicLink') ?></a></p>
        <?php endif ?> -->

        <!-- <?php if (setting('Auth.allowRegistration')) : ?>
            <p class="text-center"><?= lang('Auth.needAccount') ?> <a href="<?= url_to('register') ?>"><?= lang('Auth.register') ?></a></p>
        <?php endif ?> -->

    </form>
</div>
<footer> <?php /* Duplicado para facilitar splash: */?>
    <p>&copy; 2024 Luis Alfredo. Este trabalho está licenciado com uma licença<br> 
        <a href="/licenses.html">
            Creative Commons Attribution-NonCommercial 4.0 International</a>.<?php /* Alterar para página detalhando todas as licenças envolvidas.*/?>
    </p>    
</footer>
<?= $this->endSection() ?>