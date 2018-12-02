1. 初次執行請先執行 "npm install"安裝前端所需的套建 ; php artisan migrate 指令來建立資料表 ; composer install 指令來安裝laravel套件 ; php artisan key:generate 指令來產生app key
2. 如果使用npm run [command] 出現 cross-env錯誤， 請安裝cross-env套件到電腦環境變數 "npm install cross-env -g"
3. 如果需要備份APP資料(包含資料庫)請使用 "php artisan backup:run" 路徑在 "<base_path>\storage\app\Laravel" 下
4. 如果出現419錯誤碼，請檢察cookie的CSRF是否正常被加載，並且HTML內有調用{$csrf_field()}。註記：在Laravel 5.1版後 POST PUT DELETE不須用自行驗證，中介軟體會自動驗證，所以除了使用GET方法還需要手動驗證外，其餘皆不用。(https://laravel.tw/docs/5.1/routing#csrf-protection)