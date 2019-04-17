

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

## 前端
在專案目錄下開啟命令提示字元，輸入以下指令
```
npm run watch
```
註記：watch與dev為開發階段使用，請勿拿到線上直接使用此打包的檔案。

# 上線伺服器設定(以Apache教學)

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

    <VirtualHost *:8000>
    # 首頁位置，這邊設定在laravel專案下之public的index.php作為啟動處
    DocumentRoot "D:/phy/InduLab_laravel5/public"

    # 禁止當代理的跳板，可節省流量與增加效能
    ProxyRequests Off

            # 代理設置
            <Proxy *>
                ## 下方兩行設定為允許所有的來源訪問 ##
                # deny,allow為先檢查下方有沒有拒絕的選項如果沒有則全部允許所有訪問
                Order deny,allow
                # 允許所有來源訪問
                Allow from all
            </Proxy>

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