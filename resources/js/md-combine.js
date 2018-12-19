// 封裝插入函式
function insertString(content, start, insertStr) {
    return content.slice(0, start) + insertStr + content.slice(start);
}
class mdCombine {
    /**
     * 插入markdown標籤至內文
     * @param {String} content 內文
     * @param {String} mdTag markdown標籤名稱，例如：<strong> 或 *
     */
    appendHtml(content, mdTag) {
        // 字串只接受<[tagname]>的正規表達式，其中[tagname]只接受英文大小寫
        let reg = /<[A-Za-z]+>/
        var endTag = mdTag;
        if (reg.test(mdTag)) {
            // 將<[tagname]>分離成 < 與 [tagname]>，在插入`/`當作tag結尾
            endTag = `${mdTag.slice(0, 1)}/${mdTag.slice(1)}`;
        }
        return `${mdTag}${content}${endTag}`;
    }

    /**
     * 內容加粗體
     * @param {String} content 內容
     */
    strong(content) {
        return this.appendHtml(content, '<strong>');
    }

    /**
     * 內容加底線
     * @param {String} content 內容
     */
    underline(content) {
        return this.appendHtml(content, '<u>');
    }

    /**
     * 內容加斜體
     * @param {String} content 內容
     */
    itlic(content) {
        return this.appendHtml(content, '<i>');
    }

    /**
     * 內容加刪除線
     * @param {String} content 內容
     */
    strike(content) {
        return this.appendHtml(content, '<s>');
    }

    /**
     * 內容加粗體跟底線
     * @param {String} content 內容
     */
    strongUnderline(content) {
        return this.strong(this.underline(content));
    }

    /**
     * 改變內容顏色
     * @param {String} content 內容
     * @param {Color} color 顏色，例如#ff0000 Red rgba(255,0,0,0)
     */
    color(content, color) {
        let tag = '<span>';
        let html = this.appendHtml(content, tag);
        return insertString(html, html.indexOf(tag) + tag.length - 1, ` style="color:${color}"`);
    }
}
let md = new mdCombine();
module.exports = md;
