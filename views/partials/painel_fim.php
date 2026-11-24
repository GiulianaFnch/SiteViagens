                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/footer.php'; ?>
    <?php foreach ($painel_js ?? [] as $js): ?>
        <script src="<?= $js ?>"></script>
    <?php endforeach; ?>
</body>

</html>
