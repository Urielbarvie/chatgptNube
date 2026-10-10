                </div>
                <!-- /Panel del formulario -->
                <aside class="auth-side auth-brand-side" aria-label="Más marcas de MVB"><?php renderMarcasCuenta(array_slice($seleccionMarcas,3,3)); ?></aside>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<?php require_once __DIR__ . '/chatbot.php'; ?>
<script>window.BASE_URL = <?= json_encode(BASE_URL) ?>;</script>
<script src="<?= BASE_URL ?>/assets/js/app.js" defer></script>
<script src="<?= BASE_URL ?>/assets/js/account.js?v=<?= filemtime(__DIR__ . '/../../../assets/js/account.js') ?>" defer></script>
</body>
</html>
