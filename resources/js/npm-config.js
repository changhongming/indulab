var npmConfig = {
    /**
     * 是否為產品模式
     */
    isProd:
        process.env.NODE_ENV === 'production' ||
        process.argv.includes('-p'),
    /**
     * 是否為開發模式
     */
    isDev:
        process.env.NODE_ENV === 'development' ||
        process.argv.includes('-d'),
    /**
     * 取得npm環境變數NODE_ENV模式
     */
    env:
        process.env.NODE_ENV,
    /**
     * 取得console參數
     */
    argv:
        process.argv
}

module.exports = npmConfig;
