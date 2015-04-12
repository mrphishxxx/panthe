if (typeof(lsDisplayLibLoaded) == 'undefined') {
    var lsDisplayLibLoaded = true;

    $(function(){
        var bindEvents = function($container){
            $('[data-nc-ls-display-link]', $container).on('click', function(){
                clickHandler(this, true);
                return false;
            });
        }

        var clickHandler = function(element, callBindEvents){
            var $this = $(element);
            var href = $this.attr('href');
            var linkData = $this.attr('data-nc-ls-display-link');
            if (linkData) {
                linkData = $.parseJSON(linkData);
            }
            if (href) {
                if (linkData.displayType == 'shortpage' || (linkData.displayType == 'longpage_vertical' && typeof(linkData.subdivisionId) == 'undefined')) {
                    $.get(href, {
                        isNaked: 0 + (typeof linkData.isNaked !== 'undefined' ? linkData.isNaked : 1),
                        lsDisplayType: linkData.displayType,
                        skipTemplate: 0 + (linkData.skipTemplate ? linkData.skipTemplate : linkData.displayType == 'shortpage' && typeof(linkData.subdivisionId) != 'undefined' ? 1 : 0)
                    }, function(data){
                        var $container = [];

                        if (typeof(linkData.subdivisionId) == 'undefined') {
                            $container = $this.closest('[data-nc-ls-display-container]');
                        } else {
                            $('[data-nc-ls-display-container]').each(function(){
                                var $element = $(this);
                                var containerData = $element.attr('data-nc-ls-display-container');
                                if (containerData) {
                                    containerData = $.parseJSON(containerData);
                                    if (containerData.subdivisionId == linkData.subdivisionId) {
                                        $container = $element;
                                        return false;
                                    }
                                }

                                return true;
                            });
                        }
                        if (!$container.length) {
                            $container = $('[data-nc-ls-display-container]');
                        }
                        $container.html(data);
                        if (callBindEvents) {
                            bindEvents($container);
                        }

                        if (typeof(parent.nc_ls_quickbar) != 'undefined') {
                            var quickbar = parent.nc_ls_quickbar;
                            if (quickbar) {
                                var $quickbar = $('.nc-navbar').first();
                                $quickbar.find('.nc-quick-menu LI:eq(0) A').attr('href', quickbar.view_link);
                                $quickbar.find('.nc-quick-menu LI:eq(1) A').attr('href', quickbar.edit_link);
                                $quickbar.find('.nc-menu UL LI:eq(0) A').attr('href', quickbar.sub_admin_link);
                                $quickbar.find('.nc-menu UL LI:eq(1) A').attr('href', quickbar.template_admin_link);
                                $quickbar.find('.nc-menu UL LI:eq(2) A').attr('href', quickbar.admin_link);
                            }
                        }
                    });
                } else if (linkData.displayType == 'longpage_vertical') {
                    var scrolled = false;

                    var scrollToContainer = function(containerData, $element){
                        if (containerData) {
                            containerData = $.parseJSON(containerData);
                            if (containerData.subdivisionId == linkData.subdivisionId) {
                                $('HTML,BODY').animate({
                                    scrollTop: $element.offset().top
                                }, containerData.animationSpeed);
                                return true;
                            }
                        }

                        return false;
                    };

                    $('[data-nc-ls-display-pointer]').each(function(){
                        var $element = $(this);
                        if (scrollToContainer($element.attr('data-nc-ls-display-pointer'), $element)) {
                            scrolled = true;
                            return false;
                        }

                        return true;
                    });

                    if (!scrolled) {
                        $('[data-nc-ls-display-container]').each(function(){
                            var $element = $(this);

                            if (scrollToContainer($element.attr('data-nc-ls-display-container'), $element)) {
                                return false;
                            }

                            return true;
                        });
                    }
                }

                if (!!(window.history && history.pushState)) {
                    window.history.pushState({}, '', href);
                }

                if (typeof(linkData.onClick) == 'undefined') {
                    $this.addClass('active').siblings().removeClass('active');
                } else {
                    eval('var callback = ' + linkData.onClick);
                    callback.call($this.get(0));
                }
            }
        }

        $('[data-nc-ls-display-link]').click(function(){
            clickHandler(this, true);
            return false;
        });

        $('[data-nc-ls-display-pointer]').each(function(){
            var $this = $(this);
            var data = $.parseJSON($this.attr('data-nc-ls-display-pointer'));
            if (data.onReadyScroll) {
                setTimeout(function(){
                    $('HTML,BODY').scrollTop($this.offset().top);
                }, 1000);
                return false;
            }

            return true;
        });
    });
}