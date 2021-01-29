(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(function () {
        var E = window.Eve, EFO = E.EFO, U = EFO.U, APS = Array.prototype.slice;
        if (!U.isCallable(E.chill_player)) {

            function player() {
                return (player.is(this) ? this.init : player.F).apply(this, APS.call(arguments));
            }
            var P = U.FixCon(player).prototype;
            var templates = {};
            /* */ templates = {"player":"<div class=\"chill-player-outer\">\n    <div class=\"chill-player-wrapper\">\n        <div class=\"chill-player-zone chill-player-zone-player\" data-role=\"player\">\n            <video data-role=\"video\" ><\/video>        \n        <\/div>\n        <div class=\"chill-player-zone chill-player-zone-poster\" data-role=\"poster\">\n            <img src=\"javascript:void(0)\" data-role=\"image\" \/>\n            <div class=\"chill-player-payment-panel\">\n                <div class=\"chill-player-button chill-player-button-pplay\" data-command=\"do_play\" data-role=\"button-play-pay\">\n                    \u0421\u043c\u043e\u0442\u0440\u0435\u0442\u044c\n                <\/div>\n                <div class=\"chill-player-button chill-player-button-trailer\" data-command=\"playe_trailer\" data-role=\"button-play-trailer\">\n                    \u0422\u0440\u0435\u0439\u043b\u0435\u0440\n                <\/div>\n            <\/div>\n        <\/div>    \n        <div class=\"chill-player-zone chill-player-zone-panel\" data-role=\"panel\">\n            <div class=\"chill-player-zone-panel-content\">\n                <div class=\"chill-player-panel-element chill-player-panel-button chill-player-panel-playpause\" data-role=\"button-play-pause\" data-command=\"playpause\">\n                    <svg data-role=\"button-play-pause-icon-play\"><use xlink:href=\"#chill_player_button_play\"\/><\/svg>\n                    <svg data-role=\"button-play-pause-icon-pause\"><use xlink:href=\"#chill_player_button_pause\"\/><\/svg>\n                <\/div>\n                <div class=\"chill-player-panel-element chill-player-panel-button chill-player-panel-volume\" data-role=\"button-volume\" data-command=\"mute\">\n                    <svg data-role=\"button-play-pause-icon-play\"><use xlink:href=\"#chill_player_button_volume_0\"\/><\/svg>\n                    <svg data-role=\"button-play-pause-icon-pause\"><use xlink:href=\"#chill_player_button_volume_1\"\/><\/svg>\n                    <svg data-role=\"button-play-pause-icon-pause\"><use xlink:href=\"#chill_player_button_volume_2\"\/><\/svg>\n                <\/div>\n                <div class=\"chill-player-panel-element chill-player-panel-spinner chill-player-panel-volume-spinner\" data-role=\"spinner-volume\" data-monitor=\"volume\">\n                    <input type=\"range\">\n                <\/div>\n                <div class=\"chill-player-panel-element chill-player-panel-spinner chill-player-panel-poistion-spinner\" data-role=\"spinner-position\" data-monitor=\"position\">\n                    <input type=\"range\">\n                <\/div>\n                <div class=\"chill-player-panel-element chill-player-panel-label chill-player-panel-volume-position\" data-role=\"position\">\n                    00:00\n                <\/div>\n            <\/div>\n        <\/div>\n    <\/div>\n    <div style=\"display:none\">\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" style=\"display: none;\">\n            <symbol id=\"chill_player_button_play\" viewBox=\"0 0 335 335\">\n                <polygon points=\"22.5,0 22.5,335 312.5,167.5 \"\/>\n            <\/symbol>\n            <symbol id=\"chill_player_button_pause\" viewBox=\"0 0 365 365\">\n                <rect x=\"74.5\" width=\"73\" height=\"365\"\/>\n                <rect x=\"217.5\" width=\"73\" height=\"365\"\/>\n            <\/symbol>\n            <symbol id=\"chill_player_button_volume_2\" viewBox=\"0 0 401.963 401.963\">        \n                <path d=\"M327.106,86.816c-15.829-12.918-35.53-18.431-54.583-23.762c-35.134-9.831-60.52-16.934-60.52-63.055h-30v273.579 c-14.274-10.374-32.573-16.616-52.5-16.616c-45.491,0-82.5,32.523-82.5,72.5s37.009,72.5,82.5,72.5s82.5-32.523,82.5-72.5V69.874 c15.443,11.721,34.235,16.979,52.436,22.072c35.134,9.831,60.52,16.934,60.52,63.055h30 C354.959,124.415,345.848,102.112,327.106,86.816z\"\/>\n            <\/symbol>\n            <symbol id=\"chill_player_button_volume_1\" viewBox=\"0 0 401.963 401.963\">        \n                <path d=\"M327.106,86.816c-15.828-12.918-35.53-18.431-54.583-23.762c-35.135-9.831-60.52-16.934-60.52-63.055h-30v273.579 c-14.274-10.374-32.573-16.616-52.5-16.616c-45.49,0-82.5,32.523-82.5,72.5s37.01,72.5,82.5,72.5s82.5-32.523,82.5-72.5V139.874 c15.442,11.721,34.235,16.979,52.436,22.071c35.135,9.831,60.52,16.934,60.52,63.055h30v-70 C354.959,124.415,345.849,102.111,327.106,86.816z M324.959,155.126c-15.442-11.721-34.235-16.979-52.436-22.071 c-35.135-9.831-60.52-16.934-60.52-63.055v-0.126c15.442,11.721,34.235,16.979,52.436,22.071 c35.135,9.831,60.52,16.934,60.52,63.055V155.126z\"\/>\n            <\/symbol>\n            <symbol id=\"chill_player_button_volume_0\" viewBox=\"0 0 401.963 401.963\">\n                <path d=\"M327.106,86.816c-15.828-12.918-35.53-18.431-54.583-23.762c-35.135-9.831-60.52-16.934-60.52-63.055h-30v273.579 c-14.274-10.374-32.573-16.616-52.5-16.616c-45.49,0-82.5,32.523-82.5,72.5s37.01,72.5,82.5,72.5s82.5-32.523,82.5-72.5V209.874 c15.442,11.721,34.235,16.979,52.436,22.071c35.135,9.831,60.52,16.934,60.52,63.055h30V155 C354.959,124.415,345.849,102.111,327.106,86.816z M212.004,69.874c15.442,11.721,34.235,16.979,52.436,22.071 c35.135,9.831,60.52,16.934,60.52,63.055v0.15c-15.444-11.726-34.232-17.002-52.436-22.095c-35.135-9.831-60.52-16.934-60.52-63.055 V69.874z M324.959,225.126c-15.442-11.721-34.235-16.979-52.436-22.071c-35.135-9.831-60.52-16.934-60.52-63.055v-0.15 c15.444,11.726,34.232,17.002,52.436,22.095c35.135,9.831,60.52,16.934,60.52,63.055V225.126z\"\/>\n            <\/symbol>\n            <symbol id=\"chill_player_button_dot\" viewBox=\"0 0 335 335\">\n                <circle cx=\"167.5\" cy=\"167.5\" r=\"167.5\"\/>\n            <\/symbol>\n        <\/svg>\n    <\/div>\n<\/div>"};/*  */
            P.handle = null;
            P.init = function () {
                this.handle = jQuery(templates.player);
                debugger;
                return this;
            };

            P.set_container = function (x) {
                if (U.isObject(x) && (x instanceof jQuery)) {
                    x.html('');
                    this.handle.appendTo(x);
                }
                return this;
            };

            P.setup = function (files) {
                debugger;
            };
            window.Eve.chill_player = player;
        }
    });
})();