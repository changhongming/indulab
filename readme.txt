1. 初次執行請先執行 "npm install"安裝前端所需的套建 ; php artisan migrate 指令來建立資料表 ; composer install 指令來安裝laravel套件
2. 如果使用npm run [command] 出現 cross-env錯誤， 請安裝cross-env套件到電腦環境變數 "npm install cross-env -g"
3. 如果需要備份APP資料(包含資料庫)請使用 "php artisan backup:run" 路徑在 "<base_path>\storage\app\Laravel" 下