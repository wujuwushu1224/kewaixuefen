/*
*彬彬 2010-5-22 日整理完成，加入收藏和设为首页JQuery代码，多兼容多浏览器，希望大家能多多指导，提出意见与建议。
* HomePageFavorite-0.0.0.1
*/
var HomepageFavorite = {
    //设为首页
    Homepage: function () {
        if (document.all) {
            document.body.style.behavior = 'url(#default#homepage)';
            document.body.setHomePage(window.location.href);

        }
        else if (window.sidebar) {
            if (window.netscape) {
                try {
                    netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                }
                catch (e) {
                    alert("该操作被浏览器拒绝，如果想启用该功能，请在地址栏内输入 about:config,然后将项 signed.applets.codebase_principal_support 值该为true");
                    history.go(-1);
                }
            }
            var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
            prefs.setCharPref('browser.startup.homepage', window.location.href);
        }
    }
    ,

    //加入收获
    Favorite: function Favorite(sURL, sTitle) {
        try {
            window.external.addFavorite(sURL, sTitle);
        }
        catch (e) {
            try {
                window.sidebar.addPanel(sTitle, sURL, "");
            }
            catch (e) {
                alert("加入收藏失败,请手动添加.");
            }
        }
    }
}