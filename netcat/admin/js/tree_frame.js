/* $Id: tree_frame.js 8310 2012-10-30 12:47:58Z lemonade $ */

function dynamicTree(instanceName, containerId, source) {
	this.containerId = containerId;
    this.instanceName = instanceName;

    this.source = source;
    this.xhr = new httpRequest();

    // clear DIV
    var oDiv = document.getElementById(this.containerId);
    oDiv.innerHTML = '';

    // $nc(oDiv).css({background: '#F00', height:1000});
    // return;

    // node data info
    this.nodes = {};
    this.nodes_ids = new Array();

    // preload images
    this.preloadImage('imagePlus',  ICON_PATH + 'i_plus.png');
    this.preloadImage('imageMinus', ICON_PATH + 'i_minus.png');
    this.preloadImage('imageEmpty', ICON_PATH + 'px.gif');

    // highlight for the current (selected) node
    this.oHighlightSelected = createElement('DIV', {
        'id': 'treeHighlightSelected',
        'style.display': 'none'
    }, $nc('.menu_left_block').get(0));//document.body);

    $nc(this.oHighlightSelected).css({
    		position: 'absolute',
    		top: 0,
    		left: 0,
    		height: '30px',
    		width: '100%',
    		zIndex: 1,
    		background: '#EEE'
    });

    // highlight for the node under the mouse pointer
    this.oHighlightOver = createElement('DIV', {
        'id': 'treeHighlightOver',
        'style.display': 'none'
    }, document.body);

    // выбранный узел
    this.oSelectedNode = null;

    // load root-level nodes
    this.loadChildren();

    window[instanceName] = this;
}

// preloadImage
dynamicTree.prototype.preloadImage = function(name, src) {
    this[name] = new Image();
    this[name].src = src;
}

// addNode
/*

{nodeData}: информация об узле - объект, может иметь следующие свойства:
  nodeId (string) - идентификатор узла
  parentNodeId (string) - идентификатор родительского узла
  hasChildren (boolean) - есть или нет дочерние узлы
  image (string) - иконка
  name (string) - текст
  href (string) - путь для загрузки через urlDispatcher вида '#subdivision.add(1)'
  action (string!) - если не указан href, при нажатии выполнить указанный в
     данном свойстве js-код
  dragEnabled (boolean) - можно хватать-бросать (drag-drop)
  expand (boolean) - при загрузки открыть данный узел (показать дочерние узлы)
  buttons (object) - кнопки действий
    - image (string) - иконка
    - label (string) - описание (title, alt) кнопки
    - action (string!) - действие кнопки (js код)
    - href (string) - путь для загрузки через urlDispatcher
  parentMustBeLoaded (boolean) - добавлять узел к дереву только, когда у родителя показаны дочерние узлы
  childrenLoaded (boolen) - загруженны ли дочерние узлы, устанавливается в методе loadChildren
                            (передавать с сервера не нужно)
  checked - включен ли раздел
*/
dynamicTree.prototype.addNode = function(nodeData) {
    if (!nodeData) return;

    if (false && this.nodes_ids.indexOf(nodeData.nodeId) != -1) {
        return;
    }

    this.nodes_ids.push(nodeData.nodeId);

    if (nodeData.parentNodeId) {
        var oParent = this.getChildContainer(nodeData.parentNodeId);
    }
    else {  // add to root node
        oParent = document.getElementById(this.containerId);
    }
    // parent node not found (WTF???)
    if (!oParent) return;

    if (oParent.nodeId && nodeData.parentMustBeLoaded && !this.areChildrenLoaded(oParent.nodeId)) {

        var oPlusMinus = document.getElementById(this.containerId + '_' + oParent.nodeId + '_plusminus');
        oPlusMinus.src = this.imagePlus.src;

        this.nodes[oParent.nodeId].hasChildren = true;

        return;
    }

    if (this.nodes[oParent.nodeId]) this.nodes[oParent.nodeId].childrenLoaded = true;

    // CREATE NODE

    var oNode = document.createElement('LI');

    oNode.id = this.containerId + '_' + nodeData.nodeId;
    oNode.nodeId = nodeData.nodeId;
    oNode.dragLabel = nodeData.name;
    oNode.treeInstanceName = this.instanceName;
    oNode.className = 'menu_left_sub';
    // +-
    var oPlusMinus = createElement('IMG', {
        id: oNode.id + "_plusminus",
        className: (nodeData.hasChildren ? 'plus_minus' : 'plus_minus_none'),
        src: (nodeData.hasChildren ? this.imagePlus.src : this.imageEmpty.src),
        onclick: new Function(this.instanceName + ".toggleNode('" + nodeData.nodeId + "'); ")
    }, oNode);

    // icon
    if ( nodeData.sprite ) {
        var oIcon = createElement('I', {

            }, oNode);
        oIcon.className = 'nc-icon nc--' + nodeData.sprite;
    } else {
        var oIcon = createElement('DIV', {
            className: (nodeData.hasChildren ? 'icon_with_p-m': 'icon') + ' tree_inline icons ' + nodeData.image,
            border: 0
        }, oNode);
    }

    // show id in different style
    if (nodeData.name) {
        nodeData.name = nodeData.name.replace(/^(\d+\.)/, "<span class='node_id'>$1</span>");
    } else {
        return;
    }

    // node name
    if (nodeData.href || nodeData.action) {
        var oCaption = createElement('A', {
            className: (nodeData.className == 'menu_site' ? 'menu_site' : (nodeData.checked==0 ?  'menu_left_a unactive' : 'menu_left_a active')),
            innerHTML: nodeData.name,
            href: nodeData.href
        }, oNode);

        var action = "";
        if (!nodeData.action && nodeData.href) {
        	var action_text = '';
        	if (top.urlDispatcher) {
        		action_text += "parent.urlDispatcher.load(this.href.match('#.+$')); ";
        	}
        	action_text += this.instanceName + ".moveHighlightSelected('" + nodeData.nodeId + "'); return false;";
            action = new Function(action_text);
        }
        else {
            action = new Function(nodeData.action);
        }
        //    bindEvent(oCaption, 'click', nodeData.action);
        oCaption.onclick = action;

    } else {
        var oCaption = createElement('DIV', {
            innerHTML: nodeData.name,
            'style.display': 'inline'
        },oNode);
    }

    // add tooltip title
    if (nodeData.title) {
        oIcon.title = nodeData.title;
        oCaption.title = nodeData.title;
    }

    if (nodeData.buttons && typeof nodeData.buttons[0].icon !== 'undefined') {
        oNode.buttons = createElement('DIV', {
            'className': 'clear'
        }, oNode);

        oNode.buttons = createElement('DIV', {
                'id': oNode.id + '_buttons',
                'className': 'treeButtons',
                'style.display': 'none'
        }, oNode);

        for (var i in nodeData.buttons) {
            var btn = nodeData.buttons[i];

            if (btn.sprite) {
                var oBtnDiv = createElement('div', {
                    'innerHTML': '<i class="nc-icon nc--hovered nc--'+btn.icon+'"></i>',
                    'title':     btn.label,
                    'className': 'button',
                    'onclick':   new Function(btn.action)
                }, oNode.buttons);
            }
            else {
                var oBtnDiv = createElement('div', {
                    'title':     btn.label,
                    'className': 'button ' + btn.icon,
                    'onclick':   new Function(btn.action)
                }, oNode.buttons);
            }

            /*
            var oBtnLink = false;
            if (btn.link_to != undefined) {
                oBtnLink = createElement('A', {
                    'href': btn.link_to,
                    'target': '_blank',
                    'onclick': function() {
                        return false;
                    }}, oBtnDiv);
            }
/*
            createElement('IMG', {
                    'src': ICON_PATH + btn.image,
                    'alt': btn.label,
                    'title': btn.label,
                    'border': 0,
                    'onclick' : (btn.action ? new Function(btn.action) : new Function("parent.urlDispatcher.load('" + btn.href + "'); "))
            }, (oBtnLink ? oBtnLink : oBtnDiv));*/
        }

        bindEvent(oNode, 'mouseover', this.showNodeButtons);
        bindEvent(oNode, 'mouseout', this.hideNodeButtons);
    }

    // children container
    var oChildrenContainer = createElement('UL', {
        id: oNode.id + '_children',
        nodeId: nodeData.nodeId,
        className : 'menu_left_sub',
        'style.display': 'none'
    }, oNode);

    // add drag and drop handlers
    if (nodeData.dragEnabled && top.dragManager)  {
        top.dragManager.addDraggable(oCaption, oNode);
        top.dragManager.addDraggable(oIcon, oNode);

        var acceptDropFn = eval(nodeData.acceptDropFn);
        var onDropFn = eval(nodeData.onDropFn);

        top.dragManager.addDroppable(oCaption,
            acceptDropFn,
            onDropFn,
            {
                name: 'arrowRight',
                top: 15,
                left: 4
            });

        top.dragManager.addDroppable(oIcon,
            acceptDropFn,
            onDropFn,
            {
                name: 'line',
                top:16,
                left: 0
            });
    }

    // add resulting node to the document
    if (oParent) {
        oParent.appendChild(oNode);
        if (oParent.style.display = 'none') oParent.style.display = 'block';
        this.updatePlusMinus(oParent.nodeId);
    }

    if (nodeData.expand) {
        this.toggleNode(nodeData.nodeId);
    }

    // save settings
    this.nodes[nodeData.nodeId] = nodeData;
}

// update plus-minus (ONLY ON NODE ADDITION/DELETION!)
dynamicTree.prototype.updatePlusMinus = function(nodeId) {

    var oNodeChildren = this.getChildContainer(nodeId);
    var oPlusMinus = document.getElementById(this.containerId + '_' + nodeId + '_plusminus');

    if (!oNodeChildren || !oPlusMinus) return;

    var next_icon = $nc(oPlusMinus).next('.icons').first();

    next_icon.removeClass('icon').removeClass('icon_with_p-m');
    $nc(oPlusMinus).removeClass('plus_minus').removeClass('plus_minus_none');
    if (this.areChildrenLoaded(nodeId)) { // has children
        var $icon = $nc( this.getNode(nodeId) ).find('>i.nc-icon');
        if (oNodeChildren.style.display == 'none') {
            oPlusMinus.src = this.imagePlus.src;
            if ($icon.hasClass('nc--folder-opened')) {
                $icon.removeClass('nc--folder-opened').addClass('nc--folder');
            }
        } else {
            oPlusMinus.src = this.imageMinus.src;
            if ($icon.hasClass('nc--folder')) {
                $icon.removeClass('nc--folder').addClass('nc--folder-opened');
            }
        }
        next_icon.addClass('icon_with_p-m');
        $nc(oPlusMinus).addClass('plus_minus');
    } else {
        oPlusMinus.src = this.imageEmpty.src;
        oNodeChildren.style.display = 'none';
        next_icon.addClass('icon');
        $nc(oPlusMinus).addClass('plus_minus_none');
    }
}

// UPDATE NODE
dynamicTree.prototype.updateNode = function(nodeData) {
    var oNode = this.getNode(nodeData.nodeId);
    if (!oNode) return;

    if (nodeData.image) {
        var oIcon = oNode.getElementsByTagName('IMG').item(1);
        if (oIcon) {
			oIcon.src = ICON_PATH + nodeData.image;
		}
    }

    var oHref = oNode.getElementsByTagName('A').item(0);
    oHref.className = (oHref.className == 'menu_site' ? 'menu_site' : (nodeData.checked==0 ?  'menu_left_a unactive' : 'menu_left_a active'));
    if (nodeData.href) {
        oHref.href = nodeData.href;
    }
    if (nodeData.name) {
        // show id in different style
        nodeData.name = nodeData.name.replace(/^(\d+\.)/, "<span class='node_id'>$1</span>")
        oHref.innerHTML = nodeData.name;
    }
    if (nodeData.toggleIcon != undefined) {
        var icon_class = 'icon';
        if ($nc('div:first', oNode).hasClass('icon_with_p-m')) {
            icon_class = 'icon_with_p-m';
        };

        if (0 == nodeData.toggleIcon) {
            $nc('div.'+icon_class, oNode).removeClass(nodeData.image);
            $nc('div.'+icon_class, oNode).addClass(nodeData.image+'_disabled');
        } else {
            $nc('div.'+icon_class, oNode).addClass(nodeData.image);
            $nc('div.'+icon_class, oNode).removeClass(nodeData.image+'_disabled');
        }
    }


    if (nodeData.preview_action) {
        var button_preview = $nc('div.icon_preview:first', oNode);
        // create a function from string
        var newclick = new Function(nodeData.preview_action);
        button_preview.attr('onclick','').unbind('click');
        button_preview.click(newclick);
    }

    if (nodeData.sprite) {
        var oIcon = oNode.getElementsByTagName('I').item(0);
        oIcon.className = 'nc-icon nc--' + nodeData.sprite;
    }

    // save settings
    for (var i in nodeData) {
        this.nodes[nodeData.nodeId][i] = nodeData[i];
    }
}

dynamicTree.prototype.deleteNode = function(nodeId) {
    var oNode = this.getNode(nodeId);
    if (!oNode) {
        return;
    }

    // [todo - unbind events?]
    var parent = oNode.parentNode;
    parent.removeChild(oNode);
    this.updatePlusMinus(parent.nodeId);
    oNode = null;
    this.nodes[nodeId] = null;

    if (this.oSelectedNode) {
        this.moveHighlightSelected(this.oSelectedNode.nodeId);
    }
}

/**
  * Перемещение узла в дереве (без вызова server-side обработки, только UI)
  * Обработка на стороне сервера должна осуществляться до изменений в интерфейсе
  *
  * @param {String} ID узла, который нужно переместить
  * @param {String} позиция относительно другого узла: 'inside' или 'below'
  * @param {String} ID
  * @return {Boolean}
  */
dynamicTree.prototype.moveNode = function(nodeId, position, refNodeId) {
    // отцепляем перемещаемый узел
    var oMovedNode = this.getNode(nodeId);
    if (!oMovedNode) {
        return false;
    }
    var oOldParent = oMovedNode.parentNode;
    oOldParent.removeChild(oMovedNode);

    // куда едем?
    if (position == 'inside') { // внутрь узла refNodeId первым элементом
        var oNewParentContainer = this.getChildContainer(refNodeId);
        if (oNewParentContainer.childNodes.length) { // already loaded
            oNewParentContainer.insertBefore(oMovedNode, oNewParentContainer.firstChild);
            if (oNewParentContainer.style.display=='none') { // показать узел
                this.toggleNode(refNodeId);
            }
        }
        else { // node not loaded yet
            this.toggleNode(refNodeId, true);
        }
    }
    else if (position == 'below') { // на уровень refNodeId после этого узла
        var oRefNode = this.getNode(refNodeId),
        oNextSibling = oRefNode.nextSibling,
        oNewParentContainer = document.getElementById(oRefNode.parentNode.id);
        //while (oNextSibling.nodeType != 1) {
        //  oNextSibling = oNextSibling.nextSibling;
        //}

        //if (oNextSibling) {
        oNewParentContainer.insertBefore(oMovedNode, oNextSibling);
    // }
    // else {
    // oRefNode.parentNode.appendChild(oMovedNode);
    // }
    }
    else { // ошибочный параметр position
        return false;
    }

    this.updatePlusMinus(oOldParent.nodeId);
    this.moveHighlightSelected();

    return true;
}

// load children
dynamicTree.prototype.loadChildren = function(nodeId, moreParams) {

    nc.process_start('tree.loadChildren():'+nodeId, nodeId ? this.getNode(nodeId) : nodeId);

    var tree = this;
    setTimeout(function(){

    var url = tree.source
        + (nodeId ? nodeId : 'root')
        + (moreParams ? moreParams : '')
        + '&rand=' + Math.random()
        + '&fs=' + File_Mode;

    var on401 = "parent.loginFormShow('document.getElementById(\"treeIframe\").contentWindow."
        + tree.instanceName+".loadChildren(\""
        + (nodeId != undefined ? nodeId : '')
        + "\", \""
        + (moreParams != undefined ? moreParams : '')
        + "\")')";

        tree.xhr.isAsync = true;
        var nodes = tree.xhr.getJson(url, null, {'401': on401});

        nc.process_stop('tree.loadChildren():'+nodeId, 150);

        // double load fix
        if ( tree.areChildrenLoaded(nodeId) ) {
            return false;
        }

        if (nodes && nodes.length) {
            for (var i=0; i < nodes.length; i++) {
                if (nodes[i].parentNodeId == undefined) {
                    nodes[i].parentNodeId = nodeId;
                }
                if (File_Mode) {
                    nodes[i].href = nodes[i].href.replace('.', '_fs.');
                }
                tree.addNode(nodes[i]);
            }
        }
    }, 50);
}

dynamicTree.prototype.selectNode = function(nodeId) {
    nodeId = nodeId.replace('&fs=1', '').replace('&fs=0', '');
    var oNode = this.getNode(nodeId);



    if (oNode) {
        oParent = oNode.parentNode;
        while (oParent && oParent.id != this.containerId) {
            if (oParent.tagName=='UL' && oParent.style.display == 'none') {
                this.toggleNode(oParent.nodeId);
            }
            var oParent = oParent.parentNode;
        }
    }
    else {
        var path = this.getPath(nodeId);
        if (path && path.length) {
            for (var i=0; i < path.length; i++) {
                var forceLoad = (path[i+1] && this.getNode(path[i+1]) == null);
                this.toggleNode(path[i], forceLoad, true);
            }
            var oNode = document.getElementById(this.containerId + '_' + nodeId);
            if (!oNode) {
                this.toggleNode(path[path.length - 1], true, true);
            }
        }
        else {
            return;
        }
    }

    this.moveHighlightSelected(nodeId);
}


dynamicTree.prototype.moveHighlightSelected = function (nodeId) {

    var oNode = nodeId ? this.getNode(nodeId) : null;

    if ( ! nc.is_touch()) {
	   $nc('body').css({overflowY:'visible'});
    }

    if (this.oSelectedNode && this.oSelectedNode.nodeId != nodeId) {
        $nc(this.oSelectedNode).removeClass('nc--selected');
    }
    $nc(oNode).addClass('nc--selected');

	if (oNode) {
        this.oSelectedNode = oNode;
    }
    if (!this.oSelectedNode) {
        this.oHighlightSelected.style.display = 'none';
        return;
    }

    var top = $nc(this.oSelectedNode).offset().top;

    if (top < 1) { // скорее всего элемент находится вне документа...
        this.oHighlightSelected.style.display = 'none';
        return;
    }

    // if node is invisible,
    var node = this.oSelectedNode;
    while (node && node.id != this.containerId) {
        if (node.style.display=='none') {
            this.oHighlightSelected.style.display = 'none';
            return;
        }
        node = node.parentNode;
    }

    this.oHighlightSelected.style.top = (top-5)+'px';
    this.oHighlightSelected.style.display = '';
}

// get path to the specified node (httpRequest)
dynamicTree.prototype.getPath = function(nodeId) {
    if (!nodeId) return [];
    var url = this.source + nodeId + '&action=get_path&rand=' + Math.random();
    var nodes = this.xhr.getJson(url);
    return nodes;
}

/**
 * Toggle tree node (i.e. show or hide)
 * @param {Object} nodeId
 * @param {Object} forceLoad (used when item dropped on the empty node)
 * @param {Object} onlyIfCollapsed (toggle only if node is collapsed, i.e. open it)
 */
dynamicTree.prototype.toggleNode = function(nodeId, forceLoad, onlyIfCollapsed) {
    // click on the empty node
    if (!forceLoad && ((undefined != document.getElementById(this.containerId + '_' + nodeId + '_plusminus')) && document.getElementById(this.containerId + '_' + nodeId + '_plusminus').src==this.imageEmpty.src)) {
        return;
    }

    var oNodeChildren = document.getElementById(this.containerId + '_' + nodeId + '_children');

    if (forceLoad && oNodeChildren) { // удалить дочерние узлы и загрузить их заново
        oNodeChildren.style.display = 'none';
        oNodeChildren.innerHTML = '';
    }

    var isVisible = false;

    if ((oNodeChildren != null) && (oNodeChildren.style.display != 'none')) {
        isVisible = true;
    }

    if (onlyIfCollapsed && isVisible) {
        return;
    }

    if (!oNodeChildren || (!isVisible && !oNodeChildren.childNodes.length)) {
        this.loadChildren(nodeId);
    }

    if (oNodeChildren != null) {
        oNodeChildren.style.display = isVisible ? 'none' : '';
    }

    this.updatePlusMinus(nodeId);

    // move highlight
    if (this.oSelectedNode) {
        this.moveHighlightSelected(this.oSelectedNode.nodeId);
    }
    else {
        this.moveHighlightSelected();
    }

/* TODO */
//  if (oNodeChildren.scrollIntoView) oNodeChildren.scrollIntoView();
//  parent.window.scrollTo(0,0);
}

/**
  * Показать кнопки действий для узла в дереве
  * (также перемещает подсветку - для удобства работы с этими кнопками)
  */
dynamicTree.prototype.showNodeButtons = function(e) {

    if (top.dragManager && top.dragManager.dragInProgress) return;

    // this == node
    // проверить, сработало ли событие на ближайшем элементе
    var pos = getOffset(this);
    if (!e && window.event) e = window.event;
    if (e.pageY==undefined) {  // IE doesn't have event.pageY property :(
        e.pageY = e.clientY + document.body.scrollTop;
    }
    var distance = Math.abs(e.pageY - pos.top);
    if (distance > 25) return; // вероятно, event fired на родительском элементе

    // храним ссылку на блок с кнопками, т.к. getElementById() - медленный способ
    if (!this.buttons) return;



    // положение и видимость блока с кнопками
    this.buttons.style.right = -4 + 'px';
    this.buttons.style.display = '';
	this.buttons.style.position = 'absolute';
	this.buttons.style.top = '-5px';
	this.buttons.parentNode.style.position = 'relative';
	return;
	/*
    if (window.opera) {
        this.buttons.style.top = '1px';
    }

    // положение и видимость полоски-подсветки
    var hl = window[this.treeInstanceName].oHighlightOver;
    hl.style.top = (pos.top - 3) + 'px';
    hl.style.display = '';

    // stop propagation
    if (e && e.stopPropagation) {
        e.stopPropagation();
    }
    else {
        window.event.cancelBubble = true;
    }
    */
}

dynamicTree.prototype.hideNodeButtons = function(e) {
    // спрятать кнопку
    this.buttons.style.display = 'none';

    // спрятать полоску
    //window[this.treeInstanceName].oHighlightOver.style.display = 'none';

    // stop propagation
    if (e && e.stopPropagation) {
        e.stopPropagation();
    }
    else {
        window.event.cancelBubble = true;
    }
}

dynamicTree.prototype.getNode = function(nodeId) {
    return document.getElementById(this.containerId + '_' + nodeId);
}

dynamicTree.prototype.getChildContainer = function(nodeId) {
    if (nodeId && typeof(nodeId) == 'string') {
        nodeId = nodeId.replace(/&.*$/g, '');
    } else {
        return null;
    }
    return document.getElementById(this.containerId + '_' + nodeId + '_children');
}

dynamicTree.prototype.updateNodeHref = function(nodeId, newHref) {
    var oNode = this.getNode(nodeId);
    if (!oNode) return;
    oNode.getElementsByTagName('A')[0].href = newHref;
}

/*
 * Возвращает true, если у узла nodeId загружены деточки
 */
dynamicTree.prototype.areChildrenLoaded = function(nodeId) {
    var oNodeChildren = this.getChildContainer(nodeId);
    if (!oNodeChildren) return false;
    if (oNodeChildren.childNodes.length) return true;

    return false;
}

// ===========================================================================
// DRAG_AND_DROP HANDLERS
// ===========================================================================
function treeSitemapAcceptDrop(e) {
    // dragged item: top.dragManager.draggedInstance.type, .draggedInstance.id
    // being dropped to: top.dragManager.dropTargetObject
    var dragged = top.dragManager.draggedInstance,
    target  = top.dragManager.droppedInstance;

    // rights check
    if (!tree.nodes[target.type + '-' + target.id].dragEnabled) return false;

    // subdivision dropped on subdivision
    if (dragged.type=='sub' && target.type=='sub') return true;
    // subdivision dropped inside the site
    // 'this' refers to the target
    if (dragged.type=='sub' && target.type=='site' && this.tagName=='A') return true;
    // site dropped below the site
    if (dragged.type=='site' && target.type=='site' && this.tagName=='IMG') return true;
    // subclass dropped inside subdivision
    if (dragged.type=='subclass' && target.type=='sub' && this.tagName=='A') return true;

    // message dropped inside subdivision
    if (dragged.type=='message' && target.type=='sub' && this.tagName=='A') {
        // есть ли подходящий шаблон в разделе?
        var sc = tree.nodes[target.type+'-'+target.id].subclasses;
        for (var i=0; i < sc.length; i++) {
            if (sc[i].classId == dragged.typeNum) {
                return true;
            }
        }
    }

    return false;
}


function treeSitemapOnDrop(e) {
    var dragged = top.dragManager.draggedInstance,
    target  = top.dragManager.droppedInstance;

    /**
    * Перемещение объекта в другой раздел
    */
    if (dragged.type=='message' && target.type=='sub' && this.tagName=='A') {
        // есть ли подходящий шаблон в разделе?
        var cnt = 0, sc = tree.nodes[target.type+'-'+target.id].subclasses;
        for (var i=0; i < sc.length; i++) {
            if (sc[i].classId == dragged.typeNum) {
                cnt++;
            }
        }

        // один подходящий шаблон-в-разделе, сохраняем
        if (cnt==1) {
            top.moveMessage(dragged.typeNum, dragged.id, sc[0].subclassId);
        }
        else if (cnt > 1) { // больше одного шаблона этого типа, спросить чё надо-то
            top.showMessageToSubdivisionDialog(dragged.typeNum, dragged.id, target.id);
        }

        return;
    }

    var res = treeGenericDropHandler(top.ADMIN_PATH + 'subdivision/drag_manager_sitemap.php');

    if (res) {
        // Перемещение шаблона в разделе в другой раздел
        if (dragged.type=='subclass' && target.type=='sub' && this.tagName=='A') {
            // change url if 'subclass button' is active (refresh iframe)
            var btnId = 'subclass' + dragged.typeNum + '-' + dragged.id;

            if (top.mainView.toolbar.buttonIsActive(btnId)) {
                top.mainView.refreshIframe();
            }
            else {
                top.mainView.toolbar.removeButton(btnId);
            }
            // update tree nodeData
            var oldSubId = top.dragManager.draggedObject.subdivisionId,
            oldSubData = tree.nodes['sub-' + oldSubId];

            for (var i=0; i < oldSubData.subclasses.length; i++) {
                if (oldSubData.subclasses[i].subclassId==dragged.id) {
                    if (i == 0) { // update href if the subclass was first in old subdivision
                        var oldSubHref = (oldSubData.subclasses.length == 1)
                        ? '#subclass.add('+oldSubId+')'
                        : '#object.list('+oldSubData.subclasses[i+1].subclassId+')';

                        tree.updateNodeHref('sub-'+oldSubId, oldSubHref);
                    }
                    // remove nodedata
                    tree.nodes['sub-'+oldSubId].subclasses.splice(i, 1);
                    break; // exit from 'if'
                }
            }

            var newSubData = tree.nodes['sub-'+target.id], firstSubclass;
            if (!newSubData.subclasses || !newSubData.subclasses.length) {
                newSubData.subclasses = [];
                // update href if the subclass is first in new subdivision
                // not checked whether there are children (object.list | subclass.edit)
                firstSubclass = dragged.id;
            }
            else {
                firstSubclass = newSubData.subclasses[0].subclassId;
            }

            tree.updateNodeHref('sub-'+target.id, '#object.list('+ firstSubclass  +')');
            newSubData.subclasses.push({
                classId: dragged.typeNum,
                subclassId: dragged.id
            });
        } // of "Перемещение шаблона в разделе в другой раздел
    } // of "if res"

}


function treeClassAcceptDrop(e) {
    // dragged item: top.dragManager.draggedInstance.type, .draggedInstance.id
    // being dropped to: top.dragManager.dropTargetObject
    var dragged = top.dragManager.draggedInstance,
    target  = top.dragManager.droppedInstance;

    // class dropped inside the class group
    if (dragged.type=='dataclass' && target.type=='group' && this.tagName=='A') return true;
    return false;
}


function treeClassOnDrop(e) {
    var oNode = top.dragManager.draggedObject;
    var oOldParent = oNode.parentNode;

    treeGenericDropHandler('class/drag_manager.php');
    //  Удаляем группу шаблонов из дерева, если она пустая
    if (!oOldParent.childNodes.length) oOldParent.parentNode.style.display = 'none';
}

function treeFieldAcceptDrop(e) {
    // dragged item: top.dragManager.draggedInstance.type, .draggedInstance.id
    // being dropped to: top.dragManager.dropTargetObject
    var dragged = top.dragManager.draggedInstance,
    target  = top.dragManager.droppedInstance;

    // rights check
    if (!tree.nodes[target.type + '-' + target.id].dragEnabled) return false;

    var draggedParent = document.getElementById(tree.containerId + '_' + dragged.type + '-' + dragged.id).parentNode;
    var targetParent = document.getElementById(tree.containerId + '_' + target.type + '-' + target.id).parentNode;

    if (draggedParent.id == targetParent.id) {
        // field droped below the field of one class
        if (dragged.type=='field' && target.type=='field' && this.tagName=='I') return true;
    }

    return false;
}

function treeFieldOnDrop(e) {
    treeGenericDropHandler('field/drag_manager.php');
}

function treeSystemFieldAcceptDrop(e) {
    // dragged item: top.dragManager.draggedInstance.type, .draggedInstance.id
    // being dropped to: top.dragManager.dropTargetObject
    var dragged = top.dragManager.draggedInstance,
    target  = top.dragManager.droppedInstance;

    // rights check
    if (!tree.nodes[target.type + '-' + target.id].dragEnabled) return false;

    var draggedParent = document.getElementById(tree.containerId + '_' + dragged.type + '-' + dragged.id).parentNode;
    var targetParent = document.getElementById(tree.containerId + '_' + target.type + '-' + target.id).parentNode;

    if (draggedParent.id == targetParent.id) {
        // system field droped below the system field of one class
        if (dragged.type=='systemfield' && target.type=='systemfield' && this.tagName=='I') return true;
    }

    return false;
}

function treeSystemFieldOnDrop(e) {
    treeGenericDropHandler('field/drag_manager.php');
}


/**
  * "Стандартный" вызов server-side обработчика дропа
  *
  * @param {String}
  * @return {Boolean} success
  */
function treeGenericDropHandler(url) {
    var dragged = top.dragManager.draggedInstance,
    target  = top.dragManager.droppedInstance,
    position = (top.dragManager.dropTargetObject.tagName == 'A' ? 'inside' : 'below'),
    xhr = new httpRequest(),
    res = xhr.getJson(url,
    {
        'dragged_type': dragged.type,
        'dragged_id': dragged.id,
        'position': position,
        'target_type': target.type,
        'target_id': target.id
    } );

    if (res) {
        tree.moveNode(dragged.type+'-'+dragged.id, position, target.type+'-'+target.id);
    }
}
