! function (e, t) {
    "use strict";
    "object" == typeof module && "object" == typeof module.exports ? module.exports = t(e, document) : "function" == typeof define && define.amd ? define([], function () {
        return t(e, document)
    }) : e.plyr = t(e, document)
}("undefined" != typeof window ? window : this, function (e, t) {
    "use strict";

    function n() {
        var e, n, r, a = navigator.userAgent,
            s = navigator.appName,
            o = "" + parseFloat(navigator.appVersion),
            i = parseInt(navigator.appVersion, 10),
            l = !1,
            u = !1,
            c = !1,
            d = !1;
        return -1 !== navigator.appVersion.indexOf("Windows NT") && -1 !== navigator.appVersion.indexOf("rv:11") ? (l = !0, s = "IE", o = "11") : -1 !== (n = a.indexOf("MSIE")) ? (l = !0, s = "IE", o = a.substring(n + 5)) : -1 !== (n = a.indexOf("Chrome")) ? (c = !0, s = "Chrome", o = a.substring(n + 7)) : -1 !== (n = a.indexOf("Safari")) ? (d = !0, s = "Safari", o = a.substring(n + 7), -1 !== (n = a.indexOf("Version")) && (o = a.substring(n + 8))) : -1 !== (n = a.indexOf("Firefox")) ? (u = !0, s = "Firefox", o = a.substring(n + 8)) : (e = a.lastIndexOf(" ") + 1) < (n = a.lastIndexOf("/")) && (s = a.substring(e, n), o = a.substring(n + 1), s.toLowerCase() === s.toUpperCase() && (s = navigator.appName)), -1 !== (r = o.indexOf(";")) && (o = o.substring(0, r)), -1 !== (r = o.indexOf(" ")) && (o = o.substring(0, r)), i = parseInt("" + o, 10), isNaN(i) && (o = "" + parseFloat(navigator.appVersion), i = parseInt(navigator.appVersion, 10)), {
            name: s,
            version: i,
            isIE: l,
            isFirefox: u,
            isChrome: c,
            isSafari: d,
            isIos: /(iPad|iPhone|iPod)/g.test(navigator.platform),
            isIphone: /(iPhone|iPod)/g.test(navigator.userAgent),
            isTouch: "ontouchstart" in t.documentElement
        }
    }

    function r(e, t) {
        var n = e.media;
        if ("video" === e.type) switch (t) {
            case "video/webm":
                return !(!n.canPlayType || !n.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/no/, ""));
            case "video/mp4":
                return !(!n.canPlayType || !n.canPlayType('video/mp4; codecs="avc1.42E01E, mp4a.40.2"').replace(/no/, ""));
            case "video/ogg":
                return !(!n.canPlayType || !n.canPlayType('video/ogg; codecs="theora"').replace(/no/, ""))
        } else if ("audio" === e.type) switch (t) {
            case "audio/mpeg":
                return !(!n.canPlayType || !n.canPlayType("audio/mpeg;").replace(/no/, ""));
            case "audio/ogg":
                return !(!n.canPlayType || !n.canPlayType('audio/ogg; codecs="vorbis"').replace(/no/, ""));
            case "audio/wav":
                return !(!n.canPlayType || !n.canPlayType('audio/wav; codecs="1"').replace(/no/, ""))
        }
        return !1
    }

    function a(e) {
        if (!t.querySelectorAll('script[src="' + e + '"]').length) {
            var n = t.createElement("script");
            n.src = e;
            var r = t.getElementsByTagName("script")[0];
            r.parentNode.insertBefore(n, r)
        }
    }

    function s(e, t) {
        return Array.prototype.indexOf && -1 !== e.indexOf(t)
    }

    function o(e, t, n) {
        return e.replace(new RegExp(t.replace(/([.*+?\^=!:${}()|\[\]\/\\])/g, "\\$1"), "g"), n)
    }

    function i(e, t) {
        e.length || (e = [e]);
        for (var n = e.length - 1; n >= 0; n--) {
            var r = n > 0 ? t.cloneNode(!0) : t,
                a = e[n],
                s = a.parentNode,
                o = a.nextSibling;
            return r.appendChild(a), o ? s.insertBefore(r, o) : s.appendChild(r), r
        }
    }

    function l(e) {
        e && e.parentNode.removeChild(e)
    }

    function u(e, t) {
        e.insertBefore(t, e.firstChild)
    }

    function c(e, t) {
        for (var n in t) e.setAttribute(n, M.boolean(t[n]) && t[n] ? "" : t[n])
    }

    function d(e, n, r) {
        var a = t.createElement(e);
        c(a, r), u(n, a)
    }

    function p(e) {
        return e.replace(".", "")
    }

    function m(e, t, n) {
        if (e)
            if (e.classList) e.classList[n ? "add" : "remove"](t);
            else {
                var r = (" " + e.className + " ").replace(/\s+/g, " ").replace(" " + t + " ", "");
                e.className = r + (n ? " " + t : "")
            }
    }

    function f(e, t) {
        return !!e && (e.classList ? e.classList.contains(t) : new RegExp("(\\s|^)" + t + "(\\s|$)").test(e.className))
    }

    function y(e, n) {
        var r = Element.prototype;
        return (r.matches || r.webkitMatchesSelector || r.mozMatchesSelector || r.msMatchesSelector || function (e) {
            return -1 !== [].indexOf.call(t.querySelectorAll(e), this)
        }).call(e, n)
    }

    function b(e, t, n, r, a) {
        g(e, t, function (t) {
            n && n.apply(e, [t]), r.apply(e, [t])
        }, a)
    }

    function v(e, t, n, r, a) {
        var s = t.split(" ");
        if (M.boolean(a) || (a = !1), e instanceof NodeList)
            for (var o = 0; o < e.length; o++) e[o] instanceof Node && v(e[o], arguments[1], arguments[2], arguments[3]);
        else
            for (var i = 0; i < s.length; i++) e[r ? "addEventListener" : "removeEventListener"](s[i], n, a)
    }

    function g(e, t, n, r) {
        e && v(e, t, n, !0, r)
    }

    function h(e, t, n, r) {
        if (e && t) {
            M.boolean(n) || (n = !1);
            var a = new CustomEvent(t, {
                bubbles: n,
                detail: r
            });
            e.dispatchEvent(a)
        }
    }

    function k(e, t) {
        if (e) return t = M.boolean(t) ? t : !e.getAttribute("aria-pressed"), e.setAttribute("aria-pressed", t), t
    }

    function w(e, t) {
        return 0 === e || 0 === t || isNaN(e) || isNaN(t) ? 0 : (e / t * 100).toFixed(2)
    }

    function x() {
        var e = arguments;
        if (e.length) {
            if (1 === e.length) return e[0];
            for (var t = Array.prototype.shift.call(e), n = e.length, r = 0; r < n; r++) {
                var a = e[r];
                for (var s in a) a[s] && a[s].constructor && a[s].constructor === Object ? (t[s] = t[s] || {}, x(t[s], a[s])) : t[s] = a[s]
            }
            return t
        }
    }

    function T(e) {
        var t = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        return e.match(t) ? RegExp.$2 : e
    }

    function S(e) {
        var t = /^.*(vimeo.com\/|video\/)(\d+).*/;
        return e.match(t) ? RegExp.$2 : e
    }

    function _() {
        var e = {
                supportsFullScreen: !1,
                isFullScreen: function () {
                    return !1
                },
                requestFullScreen: function () {},
                cancelFullScreen: function () {},
                fullScreenEventName: "",
                element: null,
                prefix: ""
            },
            n = "webkit o moz ms khtml".split(" ");
        if (M.undefined(t.cancelFullScreen))
            for (var r = 0, a = n.length; r < a; r++) {
                if (e.prefix = n[r], !M.undefined(t[e.prefix + "CancelFullScreen"])) {
                    e.supportsFullScreen = !0;
                    break
                }
                if (!M.undefined(t.msExitFullscreen) && t.msFullscreenEnabled) {
                    e.prefix = "ms", e.supportsFullScreen = !0;
                    break
                }
            } else e.supportsFullScreen = !0;
        return e.supportsFullScreen && (e.fullScreenEventName = "ms" === e.prefix ? "MSFullscreenChange" : e.prefix + "fullscreenchange", e.isFullScreen = function (e) {
            switch (M.undefined(e) && (e = t.body), this.prefix) {
                case "":
                    return t.fullscreenElement === e;
                case "moz":
                    return t.mozFullScreenElement === e;
                default:
                    return t[this.prefix + "FullscreenElement"] === e
            }
        }, e.requestFullScreen = function (e) {
            return M.undefined(e) && (e = t.body), "" === this.prefix ? e.requestFullScreen() : e[this.prefix + ("ms" === this.prefix ? "RequestFullscreen" : "RequestFullScreen")]()
        }, e.cancelFullScreen = function () {
            return "" === this.prefix ? t.cancelFullScreen() : t[this.prefix + ("ms" === this.prefix ? "ExitFullscreen" : "CancelFullScreen")]()
        }, e.element = function () {
            return "" === this.prefix ? t.fullscreenElement : t[this.prefix + "FullscreenElement"]
        }), e
    }

    function E(v, E) {
        function L(e, t, n, r) {
            h(e, t, n, x({}, r, {
                plyr: He
            }))
        }

        function j(t, n) {
            E.debug && e.console && (n = Array.prototype.slice.call(n), M.string(E.logPrefix) && E.logPrefix.length && n.unshift(E.logPrefix), console[t].apply(console, n))
        }

        function V() {
            return {
                url: E.iconUrl,
                absolute: 0 === E.iconUrl.indexOf("http") || We.browser.isIE
            }
        }

        function R() {
            var e = [],
                t = V(),
                n = (t.absolute ? "" : t.url) + "#" + E.iconPrefix;
            return s(E.controls, "play-large") && e.push('<button type="button" data-plyr="play" class="plyr__play-large">', '<svg><use xlink:href="' + n + '-play" /></svg>', '<span class="plyr__sr-only">' + E.i18n.play + "</span>", "</button>"), e.push('<div class="plyr__controls">'), s(E.controls, "restart") && e.push('<button type="button" data-plyr="restart">', '<svg><use xlink:href="' + n + '-restart" /></svg>', '<span class="plyr__sr-only">' + E.i18n.restart + "</span>", "</button>"), s(E.controls, "rewind") && e.push('<button type="button" data-plyr="rewind">', '<svg><use xlink:href="' + n + '-rewind" /></svg>', '<span class="plyr__sr-only">' + E.i18n.rewind + "</span>", "</button>"), s(E.controls, "play") && e.push('<button type="button" data-plyr="play">', '<svg><use xlink:href="' + n + '-play" /></svg>', '<span class="plyr__sr-only">' + E.i18n.play + "</span>", "</button>", '<button type="button" data-plyr="pause">', '<svg><use xlink:href="' + n + '-pause" /></svg>', '<span class="plyr__sr-only">' + E.i18n.pause + "</span>", "</button>"), s(E.controls, "fast-forward") && e.push('<button type="button" data-plyr="fast-forward">', '<svg><use xlink:href="' + n + '-fast-forward" /></svg>', '<span class="plyr__sr-only">' + E.i18n.forward + "</span>", "</button>"), s(E.controls, "progress") && (e.push('<span class="plyr__progress">', '<label for="seek{id}" class="plyr__sr-only">Seek</label>', '<input id="seek{id}" class="plyr__progress--seek" type="range" min="0" max="100" step="0.1" value="0" data-plyr="seek">', '<progress class="plyr__progress--played" max="100" value="0" role="presentation"></progress>', '<progress class="plyr__progress--buffer" max="100" value="0">', "<span>0</span>% " + E.i18n.buffered, "</progress>"), E.tooltips.seek && e.push('<span class="plyr__tooltip">00:00</span>'), e.push("</span>")), s(E.controls, "current-time") && e.push('<span class="plyr__time">', '<span class="plyr__sr-only">' + E.i18n.currentTime + "</span>", '<span class="plyr__time--current">00:00</span>', "</span>"), s(E.controls, "duration") && e.push('<span class="plyr__time">', '<span class="plyr__sr-only">' + E.i18n.duration + "</span>", '<span class="plyr__time--duration">00:00</span>', "</span>"), s(E.controls, "mute") && e.push('<button type="button" data-plyr="mute">', '<svg class="icon--muted"><use xlink:href="' + n + '-muted" /></svg>', '<svg><use xlink:href="' + n + '-volume" /></svg>', '<span class="plyr__sr-only">' + E.i18n.toggleMute + "</span>", "</button>"), s(E.controls, "volume") && e.push('<span class="plyr__volume">', '<label for="volume{id}" class="plyr__sr-only">' + E.i18n.volume + "</label>", '<input id="volume{id}" class="plyr__volume--input" type="range" min="' + E.volumeMin + '" max="' + E.volumeMax + '" value="' + E.volume + '" data-plyr="volume">', '<progress class="plyr__volume--display" max="' + E.volumeMax + '" value="' + E.volumeMin + '" role="presentation"></progress>', "</span>"), s(E.controls, "captions") && e.push('<button type="button" data-plyr="captions">', '<svg class="icon--captions-on"><use xlink:href="' + n + '-captions-on" /></svg>', '<svg><use xlink:href="' + n + '-captions-off" /></svg>', '<span class="plyr__sr-only">' + E.i18n.toggleCaptions + "</span>", "</button>"), s(E.controls, "fullscreen") && e.push('<button type="button" data-plyr="fullscreen">', '<svg class="icon--exit-fullscreen"><use xlink:href="' + n + '-exit-fullscreen" /></svg>', '<svg><use xlink:href="' + n + '-enter-fullscreen" /></svg>', '<span class="plyr__sr-only">' + E.i18n.toggleFullscreen + "</span>", "</button>"), e.push("</div>"), e.join("")
        }

        function q() {
            if (We.supported.full && ("audio" !== We.type || E.fullscreen.allowAudio) && E.fullscreen.enabled) {
                var e = I.supportsFullScreen;
                e || E.fullscreen.fallback && !X() ? (Be((e ? "Native" : "Fallback") + " fullscreen enabled"), m(We.container, E.classes.fullscreen.enabled, !0)) : Be("Fullscreen not supported and fallback disabled"), We.buttons && We.buttons.fullscreen && k(We.buttons.fullscreen, !1), $()
            }
        }

        function D() {
            if ("video" === We.type) {
                B(E.selectors.captions) || We.videoContainer.insertAdjacentHTML("afterbegin", '<div class="' + p(E.selectors.captions) + '"></div>'), We.usingTextTracks = !1, We.media.textTracks && (We.usingTextTracks = !0);
                for (var e, t = "", n = We.media.childNodes, r = 0; r < n.length; r++) "track" === n[r].nodeName.toLowerCase() && ("captions" !== (e = n[r].kind) && "subtitles" !== e || (t = n[r].getAttribute("src")));
                if (We.captionExists = !0, "" === t ? (We.captionExists = !1, Be("No caption track found")) : Be("Caption track found; URI: " + t), We.captionExists) {
                    for (var a = We.media.textTracks, s = 0; s < a.length; s++) a[s].mode = "hidden";
                    if (Y(), (We.browser.isIE && We.browser.version >= 10 || We.browser.isFirefox && We.browser.version >= 31) && (Be("Detected browser with known TextTrack issues - using manual fallback"), We.usingTextTracks = !1), We.usingTextTracks) {
                        Be("TextTracks supported");
                        for (var o = 0; o < a.length; o++) {
                            var i = a[o];
                            "captions" !== i.kind && "subtitles" !== i.kind || g(i, "cuechange", function () {
                                this.activeCues[0] && "text" in this.activeCues[0] ? H(this.activeCues[0].getCueAsHTML()) : H()
                            })
                        }
                    } else if (Be("TextTracks not supported so rendering captions manually"), We.currentCaption = "", We.captions = [], "" !== t) {
                        var l = new XMLHttpRequest;
                        l.onreadystatechange = function () {
                            if (4 === l.readyState)
                                if (200 === l.status) {
                                    var e, t = [],
                                        n = l.responseText,
                                        r = "\r\n"; - 1 === n.indexOf(r + r) && (r = -1 !== n.indexOf("\r\r") ? "\r" : "\n"), t = n.split(r + r);
                                    for (var a = 0; a < t.length; a++) {
                                        e = t[a], We.captions[a] = [];
                                        var s = e.split(r),
                                            o = 0; - 1 === s[o].indexOf(":") && (o = 1), We.captions[a] = [s[o], s[o + 1]]
                                    }
                                    We.captions.shift(), Be("Successfully loaded the caption file via AJAX")
                                } else Xe(E.logPrefix + "There was a problem loading the caption file via AJAX")
                        }, l.open("get", t, !0), l.send()
                    }
                } else m(We.container, E.classes.captions.enabled)
            }
        }

        function H(e) {
            var n = B(E.selectors.captions),
                r = t.createElement("span");
            n.innerHTML = "", M.undefined(e) && (e = ""), M.string(e) ? r.innerHTML = e.trim() : r.appendChild(e), n.appendChild(r);
            n.offsetHeight
        }

        function W(e) {
            function t(e, t) {
                var n = [];
                n = e.split(" --\x3e ");
                for (var a = 0; a < n.length; a++) n[a] = n[a].replace(/(\d+:\d+:\d+\.\d+).*/, "$1");
                return r(n[t])
            }

            function n(e) {
                return t(e, 1)
            }

            function r(e) {
                if (null === e || void 0 === e) return 0;
                var t = [],
                    n = [];
                return t = e.split(","), n = t[0].split(":"), Math.floor(60 * n[0] * 60) + Math.floor(60 * n[1]) + Math.floor(n[2])
            }
            if (!We.usingTextTracks && "video" === We.type && We.supported.full && (We.subcount = 0, e = M.number(e) ? e : We.media.currentTime, We.captions[We.subcount])) {
                for (; n(We.captions[We.subcount][0]) < e.toFixed(1);)
                    if (We.subcount++, We.subcount > We.captions.length - 1) {
                        We.subcount = We.captions.length - 1;
                        break
                    } We.media.currentTime.toFixed(1) >= function (e) {
                    return t(e, 0)
                }(We.captions[We.subcount][0]) && We.media.currentTime.toFixed(1) <= n(We.captions[We.subcount][0]) ? (We.currentCaption = We.captions[We.subcount][1], H(We.currentCaption)) : H()
            }
        }

        function Y() {
            if (We.buttons.captions) {
                m(We.container, E.classes.captions.enabled, !0);
                var e = We.storage.captionsEnabled;
                M.boolean(e) || (e = E.captions.defaultActive), e && (m(We.container, E.classes.captions.active, !0), k(We.buttons.captions, !0))
            }
        }

        function U(e) {
            return We.container.querySelectorAll(e)
        }

        function B(e) {
            return U(e)[0]
        }

        function X() {
            try {
                return e.self !== e.top
            } catch (e) {
                return !0
            }
        }

        function $() {
            var e = U("input:not([disabled]), button:not([disabled])"),
                t = e[0],
                n = e[e.length - 1];
            g(We.container, "keydown", function (e) {
                9 === e.which && We.isFullscreen && (e.target !== n || e.shiftKey ? e.target === t && e.shiftKey && (e.preventDefault(), n.focus()) : (e.preventDefault(), t.focus()))
            })
        }

        function J(e, t) {
            if (M.string(t)) d(e, We.media, {
                src: t
            });
            else if (t.constructor === Array)
                for (var n = t.length - 1; n >= 0; n--) d(e, We.media, t[n])
        }

        function z() {
            if (E.loadSprite) {
                var e = V();
                e.absolute ? (Be("AJAX loading absolute SVG sprite" + (We.browser.isIE ? " (due to IE)" : "")), C(e.url, "sprite-plyr")) : Be("Sprite will be used as external resource directly")
            }
            var n = E.html;
            Be("Injecting custom controls"), n || (n = R()), n = o(n = o(n, "{seektime}", E.seekTime), "{id}", Math.floor(1e4 * Math.random()));
            var r;
            if (M.string(E.selectors.controls.container) && (r = t.querySelector(E.selectors.controls.container)), M.htmlElement(r) || (r = We.container), r.insertAdjacentHTML("beforeend", n), E.tooltips.controls)
                for (var a = U([E.selectors.controls.wrapper, " ", E.selectors.labels, " .", E.classes.hidden].join("")), s = a.length - 1; s >= 0; s--) {
                    var i = a[s];
                    m(i, E.classes.hidden, !1), m(i, E.classes.tooltip, !0)
                }
        }

        function G() {
            try {
                return We.controls = B(E.selectors.controls.wrapper), We.buttons = {}, We.buttons.seek = B(E.selectors.buttons.seek), We.buttons.play = U(E.selectors.buttons.play), We.buttons.pause = B(E.selectors.buttons.pause), We.buttons.restart = B(E.selectors.buttons.restart), We.buttons.rewind = B(E.selectors.buttons.rewind), We.buttons.forward = B(E.selectors.buttons.forward), We.buttons.fullscreen = B(E.selectors.buttons.fullscreen), We.buttons.mute = B(E.selectors.buttons.mute), We.buttons.captions = B(E.selectors.buttons.captions), We.progress = {}, We.progress.container = B(E.selectors.progress.container), We.progress.buffer = {}, We.progress.buffer.bar = B(E.selectors.progress.buffer), We.progress.buffer.text = We.progress.buffer.bar && We.progress.buffer.bar.getElementsByTagName("span")[0], We.progress.played = B(E.selectors.progress.played), We.progress.tooltip = We.progress.container && We.progress.container.querySelector("." + E.classes.tooltip), We.volume = {}, We.volume.input = B(E.selectors.volume.input), We.volume.display = B(E.selectors.volume.display), We.duration = B(E.selectors.duration), We.currentTime = B(E.selectors.currentTime), We.seekTime = U(E.selectors.seekTime), !0
            } catch (e) {
                return Xe("It looks like there is a problem with your controls HTML"), Q(!0), !1
            }
        }

        function K() {
            m(We.container, E.selectors.container.replace(".", ""), We.supported.full)
        }

        function Q(e) {
            e && s(E.types.html5, We.type) ? We.media.setAttribute("controls", "") : We.media.removeAttribute("controls")
        }

        function Z(e) {
            var t = E.i18n.play;
            if (M.string(E.title) && E.title.length && (t += ", " + E.title, We.container.setAttribute("aria-label", E.title)), We.supported.full && We.buttons.play)
                for (var n = We.buttons.play.length - 1; n >= 0; n--) We.buttons.play[n].setAttribute("aria-label", t);
            M.htmlElement(e) && e.setAttribute("title", E.i18n.frameTitle.replace("{title}", E.title))
        }

        function ee() {
            var t = null;
            We.storage = {}, O.supported && E.storage.enabled && (e.localStorage.removeItem("plyr-volume"), (t = e.localStorage.getItem(E.storage.key)) && (/^\d+(\.\d+)?$/.test(t) ? te({
                volume: parseFloat(t)
            }) : We.storage = JSON.parse(t)))
        }

        function te(t) {
            O.supported && E.storage.enabled && (x(We.storage, t), e.localStorage.setItem(E.storage.key, JSON.stringify(We.storage)))
        }

        function ne() {
            if (We.media) {
                if (We.supported.full && (m(We.container, E.classes.type.replace("{0}", We.type), !0), s(E.types.embed, We.type) && m(We.container, E.classes.type.replace("{0}", "video"), !0), m(We.container, E.classes.stopped, E.autoplay), m(We.container, E.classes.isIos, We.browser.isIos), m(We.container, E.classes.isTouch, We.browser.isTouch), "video" === We.type)) {
                    var e = t.createElement("div");
                    e.setAttribute("class", E.classes.videoWrapper), i(We.media, e), We.videoContainer = e
                }
                s(E.types.embed, We.type) && re()
            } else Xe("No media element found!")
        }

        function re() {
            var n, r = t.createElement("div"),
                s = We.type + "-" + Math.floor(1e4 * Math.random());
            switch (We.type) {
                case "youtube":
                    n = T(We.embedId);
                    break;
                case "vimeo":
                    n = S(We.embedId);
                    break;
                default:
                    n = We.embedId
            }
            for (var o = U('[id^="' + We.type + '-"]'), i = o.length - 1; i >= 0; i--) l(o[i]);
            if (m(We.media, E.classes.videoWrapper, !0), m(We.media, E.classes.embedWrapper, !0), "youtube" === We.type) We.media.appendChild(r), r.setAttribute("id", s), M.object(e.YT) ? se(n, r) : (a(E.urls.youtube.api), e.onYouTubeReadyCallbacks = e.onYouTubeReadyCallbacks || [], e.onYouTubeReadyCallbacks.push(function () {
                se(n, r)
            }), e.onYouTubeIframeAPIReady = function () {
                e.onYouTubeReadyCallbacks.forEach(function (e) {
                    e()
                })
            });
            else if ("vimeo" === We.type)
                if (We.supported.full ? We.media.appendChild(r) : r = We.media, r.setAttribute("id", s), M.object(e.Vimeo)) oe(n, r);
                else {
                    a(E.urls.vimeo.api);
                    var u = e.setInterval(function () {
                        M.object(e.Vimeo) && (e.clearInterval(u), oe(n, r))
                    }, 50)
                }
            else if ("soundcloud" === We.type) {
                var d = t.createElement("iframe");
                d.loaded = !1, g(d, "load", function () {
                    d.loaded = !0
                }), c(d, {
                    src: "https://w.soundcloud.com/player/?url=https://api.soundcloud.com/tracks/" + n,
                    id: s
                }), r.appendChild(d), We.media.appendChild(r), e.SC || a(E.urls.soundcloud.api);
                var p = e.setInterval(function () {
                    e.SC && d.loaded && (e.clearInterval(p), ie.call(d))
                }, 50)
            }
        }

        function ae() {
            We.supported.full && (qe(), De()), Z(B("iframe"))
        }

        function se(t, n) {
            We.embed = new e.YT.Player(n.id, {
                videoId: t,
                playerVars: {
                    autoplay: E.autoplay ? 1 : 0,
                    controls: We.supported.full ? 0 : 1,
                    rel: 0,
                    showinfo: 0,
                    iv_load_policy: 3,
                    cc_load_policy: E.captions.defaultActive ? 1 : 0,
                    cc_lang_pref: "en",
                    wmode: "transparent",
                    modestbranding: 1,
                    disablekb: 1,
                    origin: "*"
                },
                events: {
                    onError: function (e) {
                        L(We.container, "error", !0, {
                            code: e.data,
                            embed: e.target
                        })
                    },
                    onReady: function (t) {
                        var n = t.target;
                        We.media.play = function () {
                            n.playVideo(), We.media.paused = !1
                        }, We.media.pause = function () {
                            n.pauseVideo(), We.media.paused = !0
                        }, We.media.stop = function () {
                            n.stopVideo(), We.media.paused = !0
                        }, We.media.duration = n.getDuration(), We.media.paused = !0, We.media.currentTime = 0, We.media.muted = n.isMuted(), E.title = n.getVideoData().title, We.supported.full && We.media.querySelector("iframe").setAttribute("tabindex", "-1"), ae(), L(We.media, "timeupdate"), L(We.media, "durationchange"), e.clearInterval(Ye.buffering), Ye.buffering = e.setInterval(function () {
                            We.media.buffered = n.getVideoLoadedFraction(), (null === We.media.lastBuffered || We.media.lastBuffered < We.media.buffered) && L(We.media, "progress"), We.media.lastBuffered = We.media.buffered, 1 === We.media.buffered && (e.clearInterval(Ye.buffering), L(We.media, "canplaythrough"))
                        }, 200)
                    },
                    onStateChange: function (t) {
                        var n = t.target;
                        switch (e.clearInterval(Ye.playing), t.data) {
                            case 0:
                                We.media.paused = !0, L(We.media, "ended");
                                break;
                            case 1:
                                We.media.paused = !1, We.media.seeking && L(We.media, "seeked"), We.media.seeking = !1, L(We.media, "play"), L(We.media, "playing"), Ye.playing = e.setInterval(function () {
                                    We.media.currentTime = n.getCurrentTime(), L(We.media, "timeupdate")
                                }, 100), We.media.duration !== n.getDuration() && (We.media.duration = n.getDuration(), L(We.media, "durationchange"));
                                break;
                            case 2:
                                We.media.paused = !0, L(We.media, "pause")
                        }
                        L(We.container, "statechange", !1, {
                            code: t.data
                        })
                    }
                }
            })
        }

        function oe(t, n) {
            We.embed = new e.Vimeo.Player(n, {
                id: parseInt(t),
                loop: E.loop,
                autoplay: E.autoplay,
                byline: !1,
                portrait: !1,
                title: !1
            }), We.media.play = function () {
                We.embed.play(), We.media.paused = !1
            }, We.media.pause = function () {
                We.embed.pause(), We.media.paused = !0
            }, We.media.stop = function () {
                We.embed.stop(), We.media.paused = !0
            }, We.media.paused = !0, We.media.currentTime = 0, ae(), We.embed.getCurrentTime().then(function (e) {
                We.media.currentTime = e, L(We.media, "timeupdate")
            }), We.embed.getDuration().then(function (e) {
                We.media.duration = e, L(We.media, "durationchange")
            }), We.embed.on("loaded", function () {
                M.htmlElement(We.embed.element) && We.supported.full && We.embed.element.setAttribute("tabindex", "-1")
            }), We.embed.on("play", function () {
                We.media.paused = !1, L(We.media, "play"), L(We.media, "playing")
            }), We.embed.on("pause", function () {
                We.media.paused = !0, L(We.media, "pause")
            }), We.embed.on("timeupdate", function (e) {
                We.media.seeking = !1, We.media.currentTime = e.seconds, L(We.media, "timeupdate")
            }), We.embed.on("progress", function (e) {
                We.media.buffered = e.percent, L(We.media, "progress"), 1 === parseInt(e.percent) && L(We.media, "canplaythrough")
            }), We.embed.on("seeked", function () {
                We.media.seeking = !1, L(We.media, "seeked"), L(We.media, "play")
            }), We.embed.on("ended", function () {
                We.media.paused = !0, L(We.media, "ended")
            })
        }

        function ie() {
            We.embed = e.SC.Widget(this), We.embed.bind(e.SC.Widget.Events.READY, function () {
                We.media.play = function () {
                    We.embed.play(), We.media.paused = !1
                }, We.media.pause = function () {
                    We.embed.pause(), We.media.paused = !0
                }, We.media.stop = function () {
                    We.embed.seekTo(0), We.embed.pause(), We.media.paused = !0
                }, We.media.paused = !0, We.media.currentTime = 0, We.embed.getDuration(function (e) {
                    We.media.duration = e / 1e3, ae()
                }), We.embed.getPosition(function (e) {
                    We.media.currentTime = e, L(We.media, "timeupdate")
                }), We.embed.bind(e.SC.Widget.Events.PLAY, function () {
                    We.media.paused = !1, L(We.media, "play"), L(We.media, "playing")
                }), We.embed.bind(e.SC.Widget.Events.PAUSE, function () {
                    We.media.paused = !0, L(We.media, "pause")
                }), We.embed.bind(e.SC.Widget.Events.PLAY_PROGRESS, function (e) {
                    We.media.seeking = !1, We.media.currentTime = e.currentPosition / 1e3, L(We.media, "timeupdate")
                }), We.embed.bind(e.SC.Widget.Events.LOAD_PROGRESS, function (e) {
                    We.media.buffered = e.loadProgress, L(We.media, "progress"), 1 === parseInt(e.loadProgress) && L(We.media, "canplaythrough")
                }), We.embed.bind(e.SC.Widget.Events.FINISH, function () {
                    We.media.paused = !0, L(We.media, "ended")
                })
            })
        }

        function le() {
            "play" in We.media && We.media.play()
        }

        function ue() {
            "pause" in We.media && We.media.pause()
        }

        function ce(e) {
            return M.boolean(e) || (e = We.media.paused), e ? le() : ue(), e
        }

        function de(e) {
            M.number(e) || (e = E.seekTime), me(We.media.currentTime - e)
        }

        function pe(e) {
            M.number(e) || (e = E.seekTime), me(We.media.currentTime + e)
        }

        function me(e) {
            var t = 0,
                n = We.media.paused,
                r = fe();
            M.number(e) ? t = e : M.object(e) && s(["input", "change"], e.type) && (t = e.target.value / e.target.max * r), t < 0 ? t = 0 : t > r && (t = r), Ne(t);
            try {
                We.media.currentTime = t.toFixed(4)
            } catch (e) {}
            if (s(E.types.embed, We.type)) {
                switch (We.type) {
                    case "youtube":
                        We.embed.seekTo(t);
                        break;
                    case "vimeo":
                        We.embed.setCurrentTime(t.toFixed(0));
                        break;
                    case "soundcloud":
                        We.embed.seekTo(1e3 * t)
                }
                n && ue(), L(We.media, "timeupdate"), We.media.seeking = !0, L(We.media, "seeking")
            }
            Be("Seeking to " + We.media.currentTime + " seconds"), W(t)
        }

        function fe() {
            var e = parseInt(E.duration),
                t = 0;
            return null === We.media.duration || isNaN(We.media.duration) || (t = We.media.duration), isNaN(e) ? t : e
        }

        function ye() {
            m(We.container, E.classes.playing, !We.media.paused), m(We.container, E.classes.stopped, We.media.paused), Me(We.media.paused)
        }

        function be() {
            N = {
                x: e.pageXOffset || 0,
                y: e.pageYOffset || 0
            }
        }

        function ve() {
            e.scrollTo(N.x, N.y)
        }

        function ge(e) {
            var n = I.supportsFullScreen;
            if (n) {
                if (!e || e.type !== I.fullScreenEventName) return I.isFullScreen(We.container) ? I.cancelFullScreen() : (be(), I.requestFullScreen(We.container)), void(We.isFullscreen = I.isFullScreen(We.container));
                We.isFullscreen = I.isFullScreen(We.container)
            } else We.isFullscreen = !We.isFullscreen, t.body.style.overflow = We.isFullscreen ? "hidden" : "";
            m(We.container, E.classes.fullscreen.active, We.isFullscreen), $(We.isFullscreen), We.buttons && We.buttons.fullscreen && k(We.buttons.fullscreen, We.isFullscreen), L(We.container, We.isFullscreen ? "enterfullscreen" : "exitfullscreen", !0), !We.isFullscreen && n && ve()
        }

        function he(e) {
            if (M.boolean(e) || (e = !We.media.muted), k(We.buttons.mute, e), We.media.muted = e, 0 === We.media.volume && ke(E.volume), s(E.types.embed, We.type)) {
                switch (We.type) {
                    case "youtube":
                        We.embed[We.media.muted ? "mute" : "unMute"]();
                        break;
                    case "vimeo":
                    case "soundcloud":
                        We.embed.setVolume(We.media.muted ? 0 : parseFloat(E.volume / E.volumeMax))
                }
                L(We.media, "volumechange")
            }
        }

        function ke(e) {
            var t = E.volumeMax,
                n = E.volumeMin;
            if (M.undefined(e) && (e = We.storage.volume), (null === e || isNaN(e)) && (e = E.volume), e > t && (e = t), e < n && (e = n), We.media.volume = parseFloat(e / t), We.volume.display && (We.volume.display.value = e), s(E.types.embed, We.type)) {
                switch (We.type) {
                    case "youtube":
                        We.embed.setVolume(100 * We.media.volume);
                        break;
                    case "vimeo":
                    case "soundcloud":
                        We.embed.setVolume(We.media.volume)
                }
                L(We.media, "volumechange")
            }
            0 === e ? We.media.muted = !0 : We.media.muted && e > 0 && he()
        }

        function we(e) {
            var t = We.media.muted ? 0 : We.media.volume * E.volumeMax;
            M.number(e) || (e = E.volumeStep), ke(t + e)
        }

        function xe(e) {
            var t = We.media.muted ? 0 : We.media.volume * E.volumeMax;
            M.number(e) || (e = E.volumeStep), ke(t - e)
        }

        function Te() {
            var e = We.media.muted ? 0 : We.media.volume * E.volumeMax;
            We.supported.full && (We.volume.input && (We.volume.input.value = e), We.volume.display && (We.volume.display.value = e)), te({
                volume: e
            }), m(We.container, E.classes.muted, 0 === e), We.supported.full && We.buttons.mute && k(We.buttons.mute, 0 === e)
        }

        function Se(e) {
            We.supported.full && We.buttons.captions && (M.boolean(e) || (e = -1 === We.container.className.indexOf(E.classes.captions.active)), We.captionsEnabled = e, k(We.buttons.captions, We.captionsEnabled), m(We.container, E.classes.captions.active, We.captionsEnabled), L(We.container, We.captionsEnabled ? "captionsenabled" : "captionsdisabled", !0), te({
                captionsEnabled: We.captionsEnabled
            }))
        }

        function _e(e) {
            var t = "waiting" === e.type;
            clearTimeout(Ye.loading), Ye.loading = setTimeout(function () {
                m(We.container, E.classes.loading, t), Me(t)
            }, t ? 250 : 0)
        }

        function Ee(e) {
            if (We.supported.full) {
                var t = We.progress.played,
                    n = 0,
                    r = fe();
                if (e) switch (e.type) {
                    case "timeupdate":
                    case "seeking":
                        if (We.controls.pressed) return;
                        n = w(We.media.currentTime, r), "timeupdate" === e.type && We.buttons.seek && (We.buttons.seek.value = n);
                        break;
                    case "playing":
                    case "progress":
                        t = We.progress.buffer, n = function () {
                            var e = We.media.buffered;
                            return e && e.length ? w(e.end(0), r) : M.number(e) ? 100 * e : 0
                        }()
                }
                Ce(t, n)
            }
        }

        function Ce(e, t) {
            if (We.supported.full) {
                if (M.undefined(t) && (t = 0), M.undefined(e)) {
                    if (!We.progress || !We.progress.buffer) return;
                    e = We.progress.buffer
                }
                M.htmlElement(e) ? e.value = t : e && (e.bar && (e.bar.value = t), e.text && (e.text.innerHTML = t))
            }
        }

        function Fe(e, t) {
            if (t) {
                isNaN(e) && (e = 0), We.secs = parseInt(e % 60), We.mins = parseInt(e / 60 % 60), We.hours = parseInt(e / 60 / 60 % 60);
                var n = parseInt(fe() / 60 / 60 % 60) > 0;
                We.secs = ("0" + We.secs).slice(-2), We.mins = ("0" + We.mins).slice(-2), t.innerHTML = (n ? We.hours + ":" : "") + We.mins + ":" + We.secs
            }
        }

        function Ae() {
            if (We.supported.full) {
                var e = fe() || 0;
                !We.duration && E.displayDuration && We.media.paused && Fe(e, We.currentTime), We.duration && Fe(e, We.duration), Pe()
            }
        }

        function Ie(e) {
            Fe(We.media.currentTime, We.currentTime), e && "timeupdate" === e.type && We.media.seeking || Ee(e)
        }

        function Ne(e) {
            M.number(e) || (e = 0);
            var t = w(e, fe());
            We.progress && We.progress.played && (We.progress.played.value = t), We.buttons && We.buttons.seek && (We.buttons.seek.value = t)
        }

        function Pe(e) {
            var t = fe();
            if (E.tooltips.seek && We.progress.container && 0 !== t) {
                var n = We.progress.container.getBoundingClientRect(),
                    r = 0,
                    a = E.classes.tooltip + "--visible";
                if (e) r = 100 / n.width * (e.pageX - n.left);
                else {
                    if (!f(We.progress.tooltip, a)) return;
                    r = We.progress.tooltip.style.left.replace("%", "")
                }
                r < 0 ? r = 0 : r > 100 && (r = 100), Fe(t / 100 * r, We.progress.tooltip), We.progress.tooltip.style.left = r + "%", e && s(["mouseenter", "mouseleave"], e.type) && m(We.progress.tooltip, a, "mouseenter" === e.type)
            }
        }

        function Me(t) {
            if (E.hideControls && "audio" !== We.type) {
                var n = 0,
                    r = !1,
                    a = t,
                    o = f(We.container, E.classes.loading);
                if (M.boolean(t) || (t && t.type ? (r = "enterfullscreen" === t.type, a = s(["mousemove", "touchstart", "mouseenter", "focus"], t.type), s(["mousemove", "touchmove"], t.type) && (n = 2e3), "focus" === t.type && (n = 3e3)) : a = f(We.container, E.classes.hideControls)), e.clearTimeout(Ye.hover), a || We.media.paused || o) {
                    if (m(We.container, E.classes.hideControls, !1), We.media.paused || o) return;
                    We.browser.isTouch && (n = 3e3)
                }
                a && We.media.paused || (Ye.hover = e.setTimeout(function () {
                    (!We.controls.pressed && !We.controls.hover || r) && m(We.container, E.classes.hideControls, !0)
                }, n))
            }
        }

        function Oe(e) {
            M.object(e) && "sources" in e && e.sources.length ? (m(We.container, E.classes.ready, !1), ue(), Ne(), Ce(), Ve(), Re(function () {
                if (We.embed = null, l(We.media), "video" === We.type && We.videoContainer && l(We.videoContainer), We.container && We.container.removeAttribute("class"), "type" in e && (We.type = e.type, "video" === We.type)) {
                    var n = e.sources[0];
                    "type" in n && s(E.types.embed, n.type) && (We.type = n.type)
                }
                switch (We.supported = F(We.type), We.type) {
                    case "video":
                        We.media = t.createElement("video");
                        break;
                    case "audio":
                        We.media = t.createElement("audio");
                        break;
                    case "youtube":
                    case "vimeo":
                    case "soundcloud":
                        We.media = t.createElement("div"), We.embedId = e.sources[0].src
                }
                u(We.container, We.media), M.boolean(e.autoplay) && (E.autoplay = e.autoplay), s(E.types.html5, We.type) && (E.crossorigin && We.media.setAttribute("crossorigin", ""), E.autoplay && We.media.setAttribute("autoplay", ""), "poster" in e && We.media.setAttribute("poster", e.poster), E.loop && We.media.setAttribute("loop", "")), m(We.container, E.classes.fullscreen.active, We.isFullscreen), m(We.container, E.classes.captions.active, We.captionsEnabled), K(), s(E.types.html5, We.type) && J("source", e.sources), ne(), s(E.types.html5, We.type) && ("tracks" in e && J("track", e.tracks), We.media.load()), (s(E.types.html5, We.type) || s(E.types.embed, We.type) && !We.supported.full) && (qe(), De()), E.title = e.title, Z()
            }, !1)) : Xe("Invalid source format")
        }

        function Le() {
            function n() {
                var e = ce(),
                    t = We.buttons[e ? "play" : "pause"],
                    n = We.buttons[e ? "pause" : "play"];
                if (n = n && n.length > 1 ? n[n.length - 1] : n[0]) {
                    var r = f(t, E.classes.tabFocus);
                    setTimeout(function () {
                        n.focus(), r && (m(t, E.classes.tabFocus, !1), m(n, E.classes.tabFocus, !0))
                    }, 100)
                }
            }

            function r() {
                var e = t.activeElement;
                return e = e && e !== t.body ? t.querySelector(":focus") : null
            }

            function a(e) {
                return e.keyCode ? e.keyCode : e.which
            }

            function o(e) {
                for (var t in We.buttons) {
                    var n = We.buttons[t];
                    if (M.nodeList(n))
                        for (var r = 0; r < n.length; r++) m(n[r], E.classes.tabFocus, n[r] === e);
                    else m(n, E.classes.tabFocus, n === e)
                }
            }

            function i(e) {
                var t = a(e),
                    n = "keydown" === e.type,
                    r = n && t === u;
                if (M.number(t))
                    if (n) {
                        switch (s([48, 49, 50, 51, 52, 53, 54, 56, 57, 32, 75, 38, 40, 77, 39, 37, 70, 67], t) && (e.preventDefault(), e.stopPropagation()), t) {
                            case 48:
                            case 49:
                            case 50:
                            case 51:
                            case 52:
                            case 53:
                            case 54:
                            case 55:
                            case 56:
                            case 57:
                                r || function () {
                                    var e = We.media.duration;
                                    M.number(e) && me(e / 10 * (t - 48))
                                }();
                                break;
                            case 32:
                            case 75:
                                r || ce();
                                break;
                            case 38:
                                we();
                                break;
                            case 40:
                                xe();
                                break;
                            case 77:
                                r || he();
                                break;
                            case 39:
                                pe();
                                break;
                            case 37:
                                de();
                                break;
                            case 70:
                                ge();
                                break;
                            case 67:
                                r || Se()
                        }!I.supportsFullScreen && We.isFullscreen && 27 === t && ge(), u = t
                    } else u = null
            }
            var l = We.browser.isIE ? "change" : "input";
            if (E.keyboardShorcuts.focused) {
                var u = null;
                E.keyboardShorcuts.global && g(e, "keydown keyup", function (e) {
                    var t = a(e),
                        n = r(),
                        o = [48, 49, 50, 51, 52, 53, 54, 56, 57, 75, 77, 70, 67];
                    1 !== A().length || !s(o, t) || M.htmlElement(n) && y(n, E.selectors.editable) || i(e)
                }), g(We.container, "keydown keyup", i)
            }
            g(e, "keyup", function (e) {
                var t = a(e),
                    n = r();
                9 === t && o(n)
            }), g(t.body, "click", function () {
                m(B("." + E.classes.tabFocus), E.classes.tabFocus, !1)
            });
            for (var c in We.buttons) {
                var d = We.buttons[c];
                g(d, "blur", function () {
                    m(d, "tab-focus", !1)
                })
            }
            b(We.buttons.play, "click", E.listeners.play, n), b(We.buttons.pause, "click", E.listeners.pause, n), b(We.buttons.restart, "click", E.listeners.restart, me), b(We.buttons.rewind, "click", E.listeners.rewind, de), b(We.buttons.forward, "click", E.listeners.forward, pe), b(We.buttons.seek, l, E.listeners.seek, me), b(We.volume.input, l, E.listeners.volume, function () {
                ke(We.volume.input.value)
            }), b(We.buttons.mute, "click", E.listeners.mute, he), b(We.buttons.fullscreen, "click", E.listeners.fullscreen, ge), I.supportsFullScreen && g(t, I.fullScreenEventName, ge), b(We.buttons.captions, "click", E.listeners.captions, Se), g(We.progress.container, "mouseenter mouseleave mousemove", Pe), E.hideControls && (g(We.container, "mouseenter mouseleave mousemove touchstart touchend touchcancel touchmove enterfullscreen", Me), g(We.controls, "mouseenter mouseleave", function (e) {
                We.controls.hover = "mouseenter" === e.type
            }), g(We.controls, "mousedown mouseup touchstart touchend touchcancel", function (e) {
                We.controls.pressed = s(["mousedown", "touchstart"], e.type)
            }), g(We.controls, "focus blur", Me, !0)), g(We.volume.input, "wheel", function (e) {
                e.preventDefault();
                var t = e.webkitDirectionInvertedFromDevice,
                    n = E.volumeStep / 5;
                (e.deltaY < 0 || e.deltaX > 0) && (t ? xe(n) : we(n)), (e.deltaY > 0 || e.deltaX < 0) && (t ? we(n) : xe(n))
            })
        }

        function je() {
            if (g(We.media, "timeupdate seeking", Ie), g(We.media, "timeupdate", W), g(We.media, "durationchange loadedmetadata", Ae), g(We.media, "ended", function () {
                    "video" === We.type && E.showPosterOnEnd && ("video" === We.type && H(), me(), We.media.load())
                }), g(We.media, "progress playing", Ee), g(We.media, "volumechange", Te), g(We.media, "play pause ended", ye), g(We.media, "waiting canplay seeked", _e), E.clickToPlay && "audio" !== We.type) {
                var e = B("." + E.classes.videoWrapper);
                if (!e) return;
                e.style.cursor = "pointer", g(e, "click", function () {
                    E.hideControls && We.browser.isTouch && !We.media.paused || (We.media.paused ? le() : We.media.ended ? (me(), le()) : ue())
                })
            }
            E.disableContextMenu && g(We.media, "contextmenu", function (e) {
                e.preventDefault()
            }), g(We.media, E.events.concat(["keyup", "keydown"]).join(" "), function (e) {
                L(We.container, e.type, !0)
            })
        }

        function Ve() {
            if (s(E.types.html5, We.type)) {
                for (var e = We.media.querySelectorAll("source"), t = 0; t < e.length; t++) l(e[t]);
                We.media.setAttribute("src", E.blankUrl), We.media.load(), Be("Cancelled network requests")
            }
        }

        function Re(n, r) {
            function a() {
                clearTimeout(Ye.cleanUp), M.boolean(r) || (r = !0), M.function(n) && n.call(Ue), r && (We.init = !1, We.container.parentNode.replaceChild(Ue, We.container), t.body.style.overflow = "", L(Ue, "destroyed", !0))
            }
            if (!We.init) return null;
            switch (We.type) {
                case "youtube":
                    e.clearInterval(Ye.buffering), e.clearInterval(Ye.playing), We.embed.destroy(), a();
                    break;
                case "vimeo":
                    We.embed.unload().then(a), Ye.cleanUp = e.setTimeout(a, 200);
                    break;
                case "video":
                case "audio":
                    Q(!0), a()
            }
        }

        function qe() {
            if (!We.supported.full) return Xe("Basic support only", We.type), l(B(E.selectors.controls.wrapper)), l(B(E.selectors.buttons.play)), void Q(!0);
            var e = !U(E.selectors.controls.wrapper).length;
            e && z(), G() && (e && Le(), je(), Q(), q(), D(), ke(), Te(), Ie(), ye())
        }

        function De() {
            e.setTimeout(function () {
                L(We.media, "ready")
            }, 0), m(We.media, P.classes.setup, !0), m(We.container, E.classes.ready, !0), We.media.plyr = He, E.autoplay && le()
        }
        var He, We = this,
            Ye = {};
        We.media = v;
        var Ue = v.cloneNode(!0),
            Be = function () {
                j("log", arguments)
            },
            Xe = function () {
                j("warn", arguments)
            };
        return Be("Config", E), He = {
                getOriginal: function () {
                    return Ue
                },
                getContainer: function () {
                    return We.container
                },
                getEmbed: function () {
                    return We.embed
                },
                getMedia: function () {
                    return We.media
                },
                getType: function () {
                    return We.type
                },
                getDuration: fe,
                getCurrentTime: function () {
                    return We.media.currentTime
                },
                getVolume: function () {
                    return We.media.volume
                },
                isMuted: function () {
                    return We.media.muted
                },
                isReady: function () {
                    return f(We.container, E.classes.ready)
                },
                isLoading: function () {
                    return f(We.container, E.classes.loading)
                },
                isPaused: function () {
                    return We.media.paused
                },
                on: function (e, t) {
                    return g(We.container, e, t), this
                },
                play: le,
                pause: ue,
                stop: function () {
                    ue(), me()
                },
                restart: me,
                rewind: de,
                forward: pe,
                seek: me,
                source: function (e) {
                    if (M.undefined(e)) {
                        var t;
                        switch (We.type) {
                            case "youtube":
                                t = We.embed.getVideoUrl();
                                break;
                            case "vimeo":
                                We.embed.getVideoUrl.then(function (e) {
                                    t = e
                                });
                                break;
                            case "soundcloud":
                                We.embed.getCurrentSound(function (e) {
                                    t = e.permalink_url
                                });
                                break;
                            default:
                                t = We.media.currentSrc
                        }
                        return t || ""
                    }
                    Oe(e)
                },
                poster: function (e) {
                    "video" === We.type && We.media.setAttribute("poster", e)
                },
                setVolume: ke,
                togglePlay: ce,
                toggleMute: he,
                toggleCaptions: Se,
                toggleFullscreen: ge,
                toggleControls: Me,
                isFullscreen: function () {
                    return We.isFullscreen || !1
                },
                support: function (e) {
                    return r(We, e)
                },
                destroy: Re
            },
            function () {
                if (We.init) return null;
                if (I = _(), We.browser = n(), M.htmlElement(We.media)) {
                    ee();
                    var e = v.tagName.toLowerCase();
                    "div" === e ? (We.type = v.getAttribute("data-type"), We.embedId = v.getAttribute("data-video-id"), v.removeAttribute("data-type"), v.removeAttribute("data-video-id")) : (We.type = e, E.crossorigin = null !== v.getAttribute("crossorigin"), E.autoplay = E.autoplay || null !== v.getAttribute("autoplay"), E.loop = E.loop || null !== v.getAttribute("loop")), We.supported = F(We.type), We.supported.basic && (We.container = i(v, t.createElement("div")), We.container.setAttribute("tabindex", 0), K(), Be(We.browser.name + " " + We.browser.version), ne(), (s(E.types.html5, We.type) || s(E.types.embed, We.type) && !We.supported.full) && (qe(), De(), Z()), We.init = !0)
                }
            }(), We.init ? He : null
    }

    function C(e, n) {
        var r = new XMLHttpRequest;
        if (!M.string(n) || !M.htmlElement(t.querySelector("#" + n))) {
            var a = t.createElement("div");
            a.setAttribute("hidden", ""), M.string(n) && a.setAttribute("id", n), t.body.insertBefore(a, t.body.childNodes[0]), "withCredentials" in r && (r.open("GET", e, !0), r.onload = function () {
                a.innerHTML = r.responseText
            }, r.send())
        }
    }

    function F(e) {
        var r = n(),
            a = r.isIE && r.version <= 9,
            s = r.isIos,
            o = r.isIphone,
            i = !!t.createElement("audio").canPlayType,
            l = !!t.createElement("video").canPlayType,
            u = !1,
            c = !1;
        switch (e) {
            case "video":
                c = (u = l) && !a && !o;
                break;
            case "audio":
                c = (u = i) && !a;
                break;
            case "vimeo":
                u = !0, c = !a && !s;
                break;
            case "youtube":
                u = !0, c = !a && !s, s && !o && r.version >= 10 && (c = !0);
                break;
            case "soundcloud":
                u = !0, c = !a && !o;
                break;
            default:
                c = (u = i && l) && !a
        }
        return {
            basic: u,
            full: c
        }
    }

    function A(e) {
        if (M.string(e) ? e = t.querySelector(e) : M.undefined(e) && (e = t.body), M.htmlElement(e)) {
            var n = e.querySelectorAll("." + P.classes.setup),
                r = [];
            return Array.prototype.slice.call(n).forEach(function (e) {
                M.object(e.plyr) && r.push(e.plyr)
            }), r
        }
        return []
    }
    var I, N = {
            x: 0,
            y: 0
        },
        P = {
            enabled: !0,
            debug: !1,
            autoplay: !1,
            loop: !1,
            seekTime: 10,
            volume: 10,
            volumeMin: 0,
            volumeMax: 10,
            volumeStep: 1,
            duration: null,
            displayDuration: !0,
            loadSprite: !0,
            iconPrefix: "plyr",
            iconUrl: "http://" + location.hostname + "/files.bridge/a41m8aw32qsbjpigszv.svg",
            blankUrl: "https://cdn.plyr.io/static/blank.mp4",
            clickToPlay: !0,
            hideControls: !0,
            showPosterOnEnd: !1,
            disableContextMenu: !0,
            keyboardShorcuts: {
                focused: !0,
                global: !1
            },
            tooltips: {
                controls: !1,
                seek: !0
            },
            selectors: {
                html5: "video, audio",
                embed: "[data-type]",
                editable: "input, textarea, select, [contenteditable]",
                container: ".plyr",
                controls: {
                    container: null,
                    wrapper: ".plyr__controls"
                },
                labels: "[data-plyr]",
                buttons: {
                    seek: '[data-plyr="seek"]',
                    play: '[data-plyr="play"]',
                    pause: '[data-plyr="pause"]',
                    restart: '[data-plyr="restart"]',
                    rewind: '[data-plyr="rewind"]',
                    forward: '[data-plyr="fast-forward"]',
                    mute: '[data-plyr="mute"]',
                    captions: '[data-plyr="captions"]',
                    fullscreen: '[data-plyr="fullscreen"]'
                },
                volume: {
                    input: '[data-plyr="volume"]',
                    display: ".plyr__volume--display"
                },
                progress: {
                    container: ".plyr__progress",
                    buffer: ".plyr__progress--buffer",
                    played: ".plyr__progress--played"
                },
                captions: ".plyr__captions",
                currentTime: ".plyr__time--current",
                duration: ".plyr__time--duration"
            },
            classes: {
                setup: "plyr--setup",
                ready: "plyr--ready",
                videoWrapper: "plyr__video-wrapper",
                embedWrapper: "plyr__video-embed",
                type: "plyr--{0}",
                stopped: "plyr--stopped",
                playing: "plyr--playing",
                muted: "plyr--muted",
                loading: "plyr--loading",
                hover: "plyr--hover",
                tooltip: "plyr__tooltip",
                hidden: "plyr__sr-only",
                hideControls: "plyr--hide-controls",
                isIos: "plyr--is-ios",
                isTouch: "plyr--is-touch",
                captions: {
                    enabled: "plyr--captions-enabled",
                    active: "plyr--captions-active"
                },
                fullscreen: {
                    enabled: "plyr--fullscreen-enabled",
                    active: "plyr--fullscreen-active"
                },
                tabFocus: "tab-focus"
            },
            captions: {
                defaultActive: !1
            },
            fullscreen: {
                enabled: !0,
                fallback: !0,
                allowAudio: !1
            },
            storage: {
                enabled: !0,
                key: "plyr"
            },
            controls: ["play-large", "play", "progress", "current-time", "mute", "volume", "captions", "fullscreen"],
            i18n: {
                restart: "Restart",
                rewind: "Rewind {seektime} secs",
                play: "Play",
                pause: "Pause",
                forward: "Forward {seektime} secs",
                played: "played",
                buffered: "buffered",
                currentTime: "Current time",
                duration: "Duration",
                volume: "Volume",
                toggleMute: "Toggle Mute",
                toggleCaptions: "Toggle Captions",
                toggleFullscreen: "Toggle Fullscreen",
                frameTitle: "Player for {title}"
            },
            types: {
                embed: ["youtube", "vimeo", "soundcloud"],
                html5: ["video", "audio"]
            },
            urls: {
                vimeo: {
                    api: "https://player.vimeo.com/api/player.js"
                },
                youtube: {
                    api: "https://www.youtube.com/iframe_api"
                },
                soundcloud: {
                    api: "https://w.soundcloud.com/player/api.js"
                }
            },
            listeners: {
                seek: null,
                play: null,
                pause: null,
                restart: null,
                rewind: null,
                forward: null,
                mute: null,
                volume: null,
                captions: null,
                fullscreen: null
            },
            events: ["ready", "ended", "progress", "stalled", "playing", "waiting", "canplay", "canplaythrough", "loadstart", "loadeddata", "loadedmetadata", "timeupdate", "volumechange", "play", "pause", "error", "seeking", "seeked", "emptied"],
            logPrefix: "[Plyr]"
        },
        M = {
            object: function (e) {
                return null !== e && "object" == typeof e
            },
            array: function (e) {
                return null !== e && "object" == typeof e && e.constructor === Array
            },
            number: function (e) {
                return null !== e && ("number" == typeof e && !isNaN(e - 0) || "object" == typeof e && e.constructor === Number)
            },
            string: function (e) {
                return null !== e && ("string" == typeof e || "object" == typeof e && e.constructor === String)
            },
            boolean: function (e) {
                return null !== e && "boolean" == typeof e
            },
            nodeList: function (e) {
                return null !== e && e instanceof NodeList
            },
            htmlElement: function (e) {
                return null !== e && e instanceof HTMLElement
            },
            function: function (e) {
                return null !== e && "function" == typeof e
            },
            undefined: function (e) {
                return null !== e && void 0 === e
            }
        },
        O = {
            supported: function () {
                try {
                    e.localStorage.setItem("___test", "OK");
                    var t = e.localStorage.getItem("___test");
                    return e.localStorage.removeItem("___test"), "OK" === t
                } catch (e) {
                    return !1
                }
                return !1
            }()
        };
    return {
        setup: function (e, n) {
            function r(e, t) {
                f(t, P.classes.hook) || a.push({
                    target: e,
                    media: t
                })
            }
            var a = [],
                s = [],
                o = [P.selectors.html5, P.selectors.embed].join(",");
            if (M.string(e) ? e = t.querySelectorAll(e) : M.htmlElement(e) ? e = [e] : M.nodeList(e) || M.array(e) || M.string(e) || (M.undefined(n) && M.object(e) && (n = e), e = t.querySelectorAll(o)), M.nodeList(e) && (e = Array.prototype.slice.call(e)), !F().basic || !e.length) return !1;
            for (var i = 0; i < e.length; i++) {
                var l = e[i],
                    u = l.querySelectorAll(o);
                if (u.length)
                    for (var c = 0; c < u.length; c++) r(l, u[c]);
                else y(l, o) && r(l, l)
            }
            return a.forEach(function (e) {
                var t = e.target,
                    r = e.media,
                    a = {};
                try {
                    a = JSON.parse(t.getAttribute("data-plyr"))
                } catch (e) {}
                var o = x({}, P, n, a);
                if (!o.enabled) return null;
                var i = new E(r, o);
                if (M.object(i)) {
                    if (o.debug) {
                        var l = o.events.concat(["setup", "statechange", "enterfullscreen", "exitfullscreen", "captionsenabled", "captionsdisabled"]);
                        g(i.getContainer(), l.join(" "), function (e) {
                            console.log([o.logPrefix, "event:", e.type].join(" "), e.detail.plyr)
                        })
                    }
                    h(i.getContainer(), "setup", !0, {
                        plyr: i
                    }), s.push(i)
                }
            }), s
        },
        supported: F,
        loadSprite: C,
        get: A
    }
}),
function () {
    function e(e, t) {
        t = t || {
            bubbles: !1,
            cancelable: !1,
            detail: void 0
        };
        var n = document.createEvent("CustomEvent");
        return n.initCustomEvent(e, t.bubbles, t.cancelable, t.detail), n
    }
    "function" != typeof window.CustomEvent && (e.prototype = window.Event.prototype, window.CustomEvent = e)
}();