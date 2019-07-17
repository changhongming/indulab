

<p align="center">
<span>Laravel版本：</span><a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
</p>

# 開發所需工具
必要開發套件：

- PHP >= 7.13
- Laravel >= 5.5
- composer
- MysqlDB或MariaDB
- npm
- Apache >= 1.4 (可自行選取架設伺服器的工具，這邊僅介紹Apache)

可以直接安裝`XAMPP` => 裡面包含`PHP`、`MariaDB`、`Apache`、`phpmyadmin`.....等工具

# 開始
首先確保`composer`與`npm`已經安裝完成可使用`composer --version`與`npm --version`測試有沒有安裝成功。
- 安裝後端`PHP`套件
    ```
    composer install
    ```
- 安裝前端`JavaScript`套件
    ```
    npm install
    ```
- 設定`.env`檔案
    先將專案下的`.env.example`複製一份並改名為`.env`，並且開啟編輯`.env`檔案
    - `APP_NAME`為應用名稱。
    - `APP_ENV`為環境變數。(此變數值本身沒什麼特別的作用，但對於某些套件會依照此值做出不同的變化)
      - 一般設定為
      - `local`開發模式
      - `testing`測試模式
      - `staging`上線測試模式
      - `production`上線模式
    - `APP_KEY`為應用密鑰。可使用指令`php artisan key:generate`自動產生
    - `APP_DEBUG`為應用Debug模式。主要用於`Debugbar`這個laravel的debug套件，***注意上線時一定要將此值設為`false`***。(為`true`則開啟Debug訊息；反之`false`為關閉)
    - `APP_URL`為設定Laravel伺服器的URL位址，有一些需要監聽伺服器的套件會需要用到。
        ```
        # 應用程式設定
        APP_NAME=InduLab
        APP_ENV=local
        APP_KEY=
        APP_DEBUG=true
        APP_URL=http://localhost

        ...
        ```
- 產生APP_KEY
  ```
  php artisan key:generate
  ```
    
# Debug

## 後端
在專案目錄下開啟命令提示字元，輸入以下指令
```
php artisan serve
```

- `--host={YOUR ALLOW IP}`可設定ip白名單(預設為`localhost`)
- `--port={YOUR PORT}`可設定伺服器的監聽通訊埠(預設為8000)


建置資料表開啟命令提示字元，輸入以下指令
```
php artisan migrate
```

## 前端
1. **打包前端程式碼**
   在專案目錄下開啟命令提示字元，可以使用```npm run watch```或```npm run dev```，dev為使用webpack編譯檔案，完成後輸出檔案到指定目錄；watch則是初次編譯完成後，會持續監聽指定打包的目錄檔案是否有變更，如果有變更則重新進行打包。
    ```
    npm run watch
    ```

    註記：watch與dev為開發階段使用，請勿拿到線上直接使用此打包的檔案。
2. **相關設定**
    如果想要改變webpack相關設定，可以自行查閱專案跟目錄下的```webpack.mix.js```檔案。
    <br/>
    - ```BrowserSyc```：```host```會綁定到全位置，所以注意要將轉發頁面及UI控制板的頁面對外之```port```進行封鎖。
    <br/>
    - ```SourceMap```：只要在開模式下，目前設定將此功能開啟。此功能主要用於webpack打包後可以有效地進行Debug，可以實際顯示執行的行號與位置，但會增加檔案大小及實際上線很容易使用者可以輕易查看到你的原始碼，所以這邊建議產品模式將此功能關閉。設定將```webpackConfig```的```devtool: "inline-source-map"```去除即可。(預設的設定會自行判斷目前為開發模式或產品模式，如果為產品模式則不會設定此行，也就是說**產品模式不會有SourceMap功能**)
    <br/>
    - 其他： 其他功能部分如```laravel```內的```mix```版本```JS```及```CSS```號管控、將常用的套件打包進行抽取到```vendor.js```以減少請求同樣內容的問題、如何打包檔案及```SASS```如何編譯為```CSS```檔案等等。可依照需求自行查看文檔進行修改，這邊就不贅述了。
3. **Vue開發須知**
   - 變數命名規則：
     開發時請注意Vue內部採用小寫駝峰型(lower camel case)命名規則，例如: ``getItem``、``componentIndex`` 等。但因為 HTML 本身是無法區分大小寫的，所以在模板(templete)或HTML請使用破折號隔開(kebab case)，例如``get-item``、``component-index``等。(詳細請參考[連結](https://cn.vuejs.org/v2/style-guide/index.html#Prop-%E5%90%8D%E5%A4%A7%E5%B0%8F%E5%86%99-%E5%BC%BA%E7%83%88%E6%8E%A8%E8%8D%90))

   -  元件(component)內的``data``：
    data必須返回一個函式，而不是物件，詳細請參考[連結](https://cn.vuejs.org/v2/style-guide/index.html#%E7%BB%84%E4%BB%B6%E6%95%B0%E6%8D%AE-%E5%BF%85%E8%A6%81)。
    
   - 其他：
    其他開發注意事項可參考官方的開發風格指南[連結](https://cn.vuejs.org/v2/style-guide/index.html#%E8%A7%84%E5%88%99%E5%BD%92%E7%B1%BB)。

# 上線伺服器設定(以Apache教學 >= 1.4版)

首先記得使用`composer install`先安裝`PHP`相關的套件，設定虛擬主機(Virtaul Host)。假設伺服器欲監通訊埠(port) 8000的位置，並且允許所有主機IP連入，設定如下範例。(請先確認proxy的模組已啟用 => `httpd.conf`)

***注意：如果僅需架設單獨一個伺服器，可不用設定虛擬主機與代理，直接設定`httpd.conf`即可。***

- **apache/conf/httpd.conf**
    ```
    LoadModule proxy_module modules/mod_proxy.so
    LoadModule proxy_http_module modules/mod_proxy_http.so
    ```

- **apache/conf/extra/httpd-vhosts.conf**
    ```
    # 監聽 0.0.0.0:8000 位置
    Listen 8000

    # 虛擬主機設定
    <VirtualHost *:8000>
    # 首頁位置，這邊設定在laravel專案下之public的index.php作為啟動處
    DocumentRoot D:/phy/InduLab_laravel5/public

        # 掛載專案目錄
        <Directory "D:/phy/InduLab_laravel5">
            # 允許專案內的.htaccess檔案覆寫設定
            AllowOverride All
            # 設定允許及拒絕的Domain及IP
            <RequireAll>
                Require ip 140.125.32
            </RequireAll>
        </Directory>

        # 將一些敏感的設定檔如.htaccess、web.config拒絕存取。
        <LocationMatch “\.htaccess|web\.config”>
            Order Allow,Deny
            Deny from all
        </LocationMatch>

    # 錯誤訊息紀錄位置
    ErrorLog D:/xampp/logs/error_slope.log

    # 一般訊息紀錄位置
    CustomLog D:/xampp/logs/access_slope.log combined
    </VirtualHost>
    ```

設定完成後，即可開啟伺服器測試是否可以成功開啟，完成後接者將前端程式碼進行編譯，使用以下指令
```
npm run prod
```
以上步驟完成後即可檢視網頁是否架設成功。

# 常見問題
- ## 狀態碼419
    如果出現狀態碼419的問題，確認```.env```檔案內的```APP_KEY```及```session```相關的設定。另外在使用```POST```方法時，請務必要加上```@csrf```在表單內，伺服器會驗證裡面的```_token```欄位。
    - 確認```.env```檔案內的```APP_KEY```變數是否有正確的配置，可使用`php artisan key:generate`來配置```APP_KEY```。
    - 確認```.env```檔案內的```SESSION_LIFETIME```來設定```session```存在的時間(單位以分鐘計時)，因如果```session```過期會造成驗證```session```錯誤，會擲回```TokenMismatchException```錯誤，如果未來有需要可在```app/Exceptions/Handler.php```來處理錯誤，範例如以下程式碼。
        ```
        public function render($request, Exception $exception)
        {
            if($exception instanceof \Illuminate\Session\TokenMismatchException)    {
            /*
             * 實作例外擲回時的處理，如下方的重新導向到/login的URI
            */
            // 回到登入頁面
            return redirect('/login');
            }

        ...
        }
        ```
    - 使用```POST```方法時，請在表單內加入```@csrf```或```{!! csrf_field() !!}```在```blade.php```檔案內，範例如下
        ```
        <form method="POST" action="/data">
            @csrf
            <input type="text" name="data1" id="data1"/>
            <input type="text" name="data2" id="data2"/>
            {{-- ... --}}
        </form>
        ```
        如果使用```ajax```方法請自行在```POST```時自己加入```_token```欄位，並放入目前的```token```字串給伺服器。或在請求的```header```內加入```X-CSRF-TOKEN```欄位並放入```token```。
- ## 狀態碼 500
    如果出現狀態碼500，請確認後端程式碼是否錯誤，如果擲回例外而沒又去接取時，便會跳出狀態碼500。
- ## npm執行時出現cross-env錯誤
    如果使用```npm run {command}``` 出現 ```cross-env```錯誤， 請安裝```cross-env```套件到電腦環境變數 ```npm install cross-env -g```
- ## 使用```php artisan```相關指令無法找到新增的類別(class)檔案
    請先執行指令```composer dump-autoload```，讓composer重新自動加載目錄下檔案。(一般會發生在```seed```命令下)

# 自定義指令說明(本專案)
以下指令都會配置在```php artisan {command}``` 下面，所以命令開頭請自行加上```php artisan```。
  1. ```make:hash {待加密密碼}```：
   將輸入的密碼進行hash加密，並將結果印出在命令提示視窗上。
        ```
        D:\workspace\master\InduLab_laravel5>php artisan make:hash 123456
        $2y$10$U25mbHdNnM.Xu/.rB8jqvu6NJZAMvuErR3p7xF7LPqZTLT/sh.9Yq
        ```
