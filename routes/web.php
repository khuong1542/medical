<?php

Route::prefix('admin')->group(function () {
	require __DIR__.'/admin.php';
});
require __DIR__.'/client.php';
require __DIR__.'/auth.php';
