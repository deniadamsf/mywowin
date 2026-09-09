# ==============================================================================
# SCRIPT SINKRONISASI 1-KLIK VIA SSH / SCP KE HOSTINGER (ZERO DATABASE IMPACT)
# ==============================================================================
# Script ini secara otomatis mengunggah file kode terbaru ke server Hostinger
# menggunakan SSH Key (alias: mywowin) tanpa menimpa .env ataupun database live.
# ==============================================================================

Write-Host "=====================================================" -ForegroundColor Green
Write-Host " [SYNC] CODE LOCALHOST -> HOSTINGER (MY WOWIN) " -ForegroundColor Cyan
Write-Host "=====================================================" -ForegroundColor Green
Write-Host "Proteksi: File .env dan Database Pengguna TIDAK DISENTUH." -ForegroundColor Yellow

$LocalRoot = "$PSScriptRoot\public_html (web fronted + backend)"
$RemoteTarget = "mywowin:~/domains/mywowin.com/public_html"

if (-not (Test-Path $LocalRoot)) {
    Write-Error "Direktori $LocalRoot tidak ditemukan!"
    exit 1
}

Write-Host "`nMengunggah file aplikasi (app, config, resources, routes, migrations)..." -ForegroundColor Cyan

# 1. Pastikan folder tujuan di remote server sudah ada
ssh -T mywowin "mkdir -p ~/domains/mywowin.com/public_html/app/Services ~/domains/mywowin.com/public_html/resources/views/admin/orders ~/domains/mywowin.com/public_html/app/Mail ~/domains/mywowin.com/public_html/resources/views/public/emails ~/domains/mywowin.com/public_html/resources/views/public/rewards ~/domains/mywowin.com/public_html/resources/views/public/members ~/domains/mywowin.com/public_html/resources/views/public/orders ~/domains/mywowin.com/public_html/resources/views/public/trackings ~/domains/mywowin.com/public_html/resources/views/public/products ~/domains/mywowin.com/public_html/resources/views/admin/reviews ~/domains/mywowin.com/public_html/resources/views/superadmin/reviews ~/domains/mywowin.com/public_html/database/migrations"

# 2. Upload file-file inti menggunakan SCP
scp "$LocalRoot\app\Mail\ResetPasswordEmail.php" "$RemoteTarget/app/Mail/"
scp "$LocalRoot\app\Mail\WelcomeEmail.php" "$RemoteTarget/app/Mail/"
scp "$LocalRoot\resources\views\public\emails\reset_password.blade.php" "$RemoteTarget/resources/views/public/emails/"
scp "$LocalRoot\resources\views\public\emails\welcome.blade.php" "$RemoteTarget/resources/views/public/emails/"
scp "$LocalRoot\resources\views\public\faq\policy.blade.php" "$RemoteTarget/resources/views/public/faq/"
scp "$LocalRoot\resources\views\public\rewards\index.blade.php" "$RemoteTarget/resources/views/public/rewards/"
scp "$LocalRoot\resources\views\public\members\member.blade.php" "$RemoteTarget/resources/views/public/members/"
scp "$LocalRoot\resources\views\public\orders\index.blade.php" "$RemoteTarget/resources/views/public/orders/"
scp "$LocalRoot\resources\views\public\trackings\index.blade.php" "$RemoteTarget/resources/views/public/trackings/"
scp "$LocalRoot\resources\views\public\trackings\nota.blade.php" "$RemoteTarget/resources/views/public/trackings/"
scp "$LocalRoot\resources\views\public\trackings\enota.blade.php" "$RemoteTarget/resources/views/public/trackings/"
scp "$LocalRoot\resources\views\public\layouts\footer.blade.php" "$RemoteTarget/resources/views/public/layouts/"
scp "$LocalRoot\resources\views\public\products\detail.blade.php" "$RemoteTarget/resources/views/public/products/"
scp "$LocalRoot\app\Http\Middleware\CheckUserIsActive.php" "$RemoteTarget/app/Http/Middleware/"
scp "$LocalRoot\app\Http\Controllers\Api\AuthController.php" "$RemoteTarget/app/Http/Controllers/Api/"
scp "$LocalRoot\app\Http\Controllers\Api\RewardController.php" "$RemoteTarget/app/Http/Controllers/Api/"
scp "$LocalRoot\app\Http\Controllers\Api\CartController.php" "$RemoteTarget/app/Http/Controllers/Api/"
scp "$LocalRoot\app\Http\Controllers\Api\OrderController.php" "$RemoteTarget/app/Http/Controllers/Api/"
scp "$LocalRoot\app\Http\Controllers\Api\ReviewController.php" "$RemoteTarget/app/Http/Controllers/Api/"
scp "$LocalRoot\app\Http\Controllers\Admin\AdminReviewController.php" "$RemoteTarget/app/Http/Controllers/Admin/"
scp "$LocalRoot\app\Http\Controllers\Superadmin\SuperReviewController.php" "$RemoteTarget/app/Http/Controllers/Superadmin/"
scp "$LocalRoot\app\Http\Controllers\Superadmin\SuperBranchSettingController.php" "$RemoteTarget/app/Http/Controllers/Superadmin/"
scp "$LocalRoot\app\Http\Controllers\Auth\RegisterController.php" "$RemoteTarget/app/Http/Controllers/Auth/"
scp "$LocalRoot\app\Http\Controllers\Auth\ForgotPasswordController.php" "$RemoteTarget/app/Http/Controllers/Auth/"
scp "$LocalRoot\app\Http\Controllers\Public\AccountDeletionController.php" "$RemoteTarget/app/Http/Controllers/Public/"
scp "$LocalRoot\app\Http\Controllers\Public\TrackingController.php" "$RemoteTarget/app/Http/Controllers/Public/"
scp "$LocalRoot\app\Http\Controllers\Public\ClaimedRewardController.php" "$RemoteTarget/app/Http/Controllers/Public/"
scp "$LocalRoot\app\Http\Controllers\Public\OrderController.php" "$RemoteTarget/app/Http/Controllers/Public/"
scp "$LocalRoot\app\Http\Controllers\Public\PublicProductController.php" "$RemoteTarget/app/Http/Controllers/Public/"
scp "$LocalRoot\resources\views\public\delete_account.blade.php" "$RemoteTarget/resources/views/public/"
scp "$LocalRoot\resources\views\admin\reviews\index.blade.php" "$RemoteTarget/resources/views/admin/reviews/"
scp "$LocalRoot\resources\views\superadmin\reviews\index.blade.php" "$RemoteTarget/resources/views/superadmin/reviews/"
scp "$LocalRoot\resources\views\admin\branch_settings\index.blade.php" "$RemoteTarget/resources/views/admin/branch_settings/"
scp "$LocalRoot\resources\views\superadmin\branch_settings\index.blade.php" "$RemoteTarget/resources/views/superadmin/branch_settings/"
scp "$LocalRoot\resources\views\admin\layouts\sidebar.blade.php" "$RemoteTarget/resources/views/admin/layouts/"
scp "$LocalRoot\resources\views\superadmin\layouts\sidebar.blade.php" "$RemoteTarget/resources/views/superadmin/layouts/"
scp "$LocalRoot\app\Models\User.php" "$RemoteTarget/app/Models/"
scp "$LocalRoot\app\Models\Membership.php" "$RemoteTarget/app/Models/"
scp "$LocalRoot\app\Models\Order.php" "$RemoteTarget/app/Models/"
scp "$LocalRoot\app\Models\Product.php" "$RemoteTarget/app/Models/"
scp "$LocalRoot\app\Models\BranchSetting.php" "$RemoteTarget/app/Models/"
scp "$LocalRoot\app\Models\Review.php" "$RemoteTarget/app/Models/"
scp "$LocalRoot\app\Services\JntService.php" "$RemoteTarget/app/Services/"
scp "$LocalRoot\app\Http\Controllers\Admin\AdminOrderController.php" "$RemoteTarget/app/Http/Controllers/Admin/"
scp "$LocalRoot\app\Http\Controllers\Superadmin\SuperOrderController.php" "$RemoteTarget/app/Http/Controllers/Superadmin/"
scp "$LocalRoot\resources\views\admin\orders\index.blade.php" "$RemoteTarget/resources/views/admin/orders/"
scp "$LocalRoot\resources\views\admin\orders\shipping_label.blade.php" "$RemoteTarget/resources/views/admin/orders/"
scp "$LocalRoot\routes\api.php" "$RemoteTarget/routes/"
scp "$LocalRoot\routes\web.php" "$RemoteTarget/routes/"
scp "$LocalRoot\config\mail.php" "$RemoteTarget/config/"
scp "$LocalRoot\config\app.php" "$RemoteTarget/config/"
scp "$LocalRoot\config\jnt.php" "$RemoteTarget/config/"
scp "$LocalRoot\database\migrations\2026_08_20_000001_add_last_daily_claim_at_to_users_table.php" "$RemoteTarget/database/migrations/"
scp "$LocalRoot\database\migrations\2026_08_20_000002_add_points_columns_to_orders_table.php" "$RemoteTarget/database/migrations/"
scp "$LocalRoot\database\migrations\2026_08_31_000001_create_reviews_table.php" "$RemoteTarget/database/migrations/"
scp "$LocalRoot\database\migrations\2026_08_31_000002_add_google_maps_review_url_to_branch_settings_table.php" "$RemoteTarget/database/migrations/"
scp "$LocalRoot\database\migrations\2026_09_09_000001_add_jnt_shipping_to_orders_table.php" "$RemoteTarget/database/migrations/"
scp "$LocalRoot\.htaccess" "$RemoteTarget/"

Write-Host "`nMenjalankan migrasi database di server..." -ForegroundColor Cyan
ssh -T mywowin "cd ~/domains/mywowin.com/public_html && php artisan migrate --force"

Write-Host "`nMembersihkan cache konfigurasi, cache data, routes, dan views di server..." -ForegroundColor Cyan
ssh -T mywowin "cd ~/domains/mywowin.com/public_html && php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear"

Write-Host "`n[SELESAI] Sinkronisasi Berhasil Selesai!" -ForegroundColor Green
Write-Host "Server Hostinger (mywowin.com) sudah diperbarui dengan kode terbaru." -ForegroundColor Cyan
