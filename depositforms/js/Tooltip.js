/**
 * Copyright (c) 2006, Opera Software ASA
 * All rights reserved.
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of Opera Software ASA nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY OPERA SOFTWARE ASA AND CONTRIBUTORS ``AS IS'' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL OPERA SOFTWARE ASA AND CONTRIBUTORS BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * Tooltip library.
 *
 * Tooltips are added to a host element in order to
 * offer a description of what the element is or contains.
 * The tooltip is actually added to the body element, so
 * as to not conflict with absolute positioning. It is
 * then offset relative to the viewport, toward it's host
 * element or where the author chooses.
 *
 * An addTooltip method is added to the HTMLElement prototype.
 *
 * Uses:
 *   Opera Animation Library (animation.js)
 *
 * @author Hans S. Toemmerholt, Opera Software
 */

/**
 * Add a tooltip to the element
 * The parameters to the function mirror those 
 * in the Tooltip constructor.
 */
HTMLElement.prototype.addTooltip = function ( content, x, y, hideTime, hiddenTime, animated, mousePos, style ) {
  if (! content) return;
  return new Tooltip ( this, content, x, y, hideTime, hiddenTime, animated, mousePos, style );
}
//opera.debug = function(t) {
	//opera.postError(t);
//}
/**
 * Class for encapsulating one tooltip
 * @constructor
 * @param element  HTMLElement this tooltip is attached to.
 * @param content  Either a string or an HTMLElement which is displayed inside the tooltip.
 * @param x        X position of the tooltip, relative to the viewport.
 * @param y        Y position of the tooltip, relative to the viewport.
 * @param hideTime How long the tooltip is visible before hiding automatically. If 0, the tooltip will be visible until the cursor leaves the host element.
 * @param hiddenTime  How long the tooltip should stay away after it's been hidden.
 * @param animated  Wether or not the tooltip should fade in and out of view.
 * @param mousePos Wether or not the tooltip should be positioned by the mouse cursor. If true, the x and y positions are ignored.
 * @param style    Dictionary of HTML DOM CSS key/value pairs.
 * @constructor
 * @author Hans S. Tømmerholt
 */
function Tooltip ( element, content, x, y, hideTime, hiddenTime, animated, mousePos, style ) {

  var element = element;
  var content = 
    ( typeof(content) == 'string' ? document.createTextNode(content) : content );
  var animated = animated;
  var mousePos =  mousePos;
  var style = style;
  var tooltip;
  var showAnim;  
  var hideTime = hideTime;
  var hideAnim
  var hideTimer;
  var mousein = false;
  var hiddenTime = hiddenTime; 
  var stayHidden = false;

  var hiddenTimer;

  init();

  /**
   * Set up the tooltip
   */
  function init() {

    tooltip = document.createElement('div');

    setupStyle();

    tooltip.appendChild(content);

    if ( ! mousePos ) {
      if ( ! x ) { x = 0; }
      if ( ! y ) { y = 0; }

      tooltip.style.left = (x + findPosX(element)) + 'px';
      tooltip.style.top =  (y + findPosY(element)) + 'px';
    }

    tooltip.className = 'tooltip';
    tooltip.style.position = 'absolute';
    tooltip.style.display = 'none';

    setupAnims();

    document.body.appendChild(tooltip);

    element.addEventListener( 
        'mouseover', 
        function (evt) { 
          /*opera.debug('over!' + evt.target);*/ 
          //opera.debug( 'over from: ' + evt.relatedTarget)
          if ( evt.relatedTarget == tooltip ) { return; }
          mousein = true;
          if ( mousePos ) {
            tooltip.style.left = (evt.pageX + 10 ) + 'px';
            tooltip.style.top  = (evt.pageY + 10 ) + 'px';
          }
          showTooltip(); 
        }, 
        false 
    );
    element.addEventListener( 
        'mouseout', 
        function (evt) { 
          /*opera.debug('out! ' + evt.target);*/ 
          //opera.debug('out to: ' + evt.relatedTarget)
          mousein = false;
          hideTooltip(); }, 
        false 
    );

  }

  function setupStyle() {
    if ( style ) {
      for ( s in style ) {
        tooltip.style[s] = style[s];
        //opera.debug(s);
      }
    }
  }

  function setupAnims() {
    //Add fade animations
    if ( HTMLElement.prototype.createAnimation && animated ) {
      tooltip.style.opacity = '0';
      showAnim = tooltip.createAnimation();
      showAnim.addAnimation( 'opacity', '0.0', '1.0' );
      hideAnim = tooltip.createAnimation();
      hideAnim.addAnimation( 'opacity', '1.0', '0.0' );
      //opera.debug(hideAnim.addEventListener)
      element.addEventListener( 'OAnimationFinish', function () { tooltip.style.display = 'none'; }, false );
      //hideAnim.onfinish = function () { tooltip.style.display = 'none'; }
    }
  }

  function clearAnims() {
    if ( HTMLElement.prototype.createAnimation ) {
      if ( showAnimation ) { 
        showAnim.remove(); 
        showAnim = null;
      }
      if ( hideAnimation ) { 
        hideAnim.remove(); 
        hideAnim = null;
      }
    }
  }

  /** @return  HTMLElement this tooltip is attached to. **/
  this.getElement = function () { return element; }
  /** @return  Content node of this tooltip**/
  this.getContent = function () { return content; }
  /** @return  Wether or not this tooltip fades in and out. **/
  this.isAnimated = function () { return animated; }
  /** @return  Wether or not this tooltip is positioned by the mouse. **/
  this.isMousePositioned = function () { return mousePos; }
  /** @return  How many seconds before the tooltip hides itself. **/
  this.getHideTime = function () { return hideTime; }
  /** @return  How many seconds the tooltip stays away after it's been hid. **/
  this.getHiddenDelayTime = function () { return hiddenTime; }
  /** @return  Style of this tooltip **/
  this.getStyle = function () { return style; }

  /** Set the content of this tooltip */
  this.setContent = function (newContent) {
    var content = 
      ( typeof(content) == 'string' ? document.createTextNode(content) : content );
    tooltip.innerHTML = '';
    tooltip.appendChild(content);

  }

  /** Set wether or not this tooltip should fade in and out. */
  this.setAnimated = function (animate) {
    if ( ! animated && animate ) {
      animated = animate;
      //opera.debug('setting up anims');
      setupAnims();
    }

    if ( animated && ! animate ) {
      animated = animate;
      clearAnims();
    }

  }

  /** Set how long the tooltip should stay visible before hiding itself */
  this.setHideTime = function ( time ) { hideTime = time; }
  /** Set wether or not the tooltip should be positioned by the mouse. **/
  this.setMousePositioned = function ( useMousePos ) { mousePos = useMousePos; }
  /** Set how long the tooltip should stay away after it's been hidden. **/
  this.setHiddenDelayTime = function ( newHiddenTime ) { hiddenTime = newHiddenTime; }

  /** Set the style of this tooltip. Will not remove any old style. **/
  this.setStyle = function (newStyle) {
    style = newStyle;
    setupStyle();
  }

  function showTooltip() {

    if ( stayHidden || ! mousein ) { 
	//opera.debug('Still hidden ' + stayHidden + ' ' + mousein );
      return; 
    }

    tooltip.style.display = 'block';

    if ( showAnim ) { showAnim.run(); }

    if ( hideTime && hideTime > 0 ) {
      hideTimer = setTimeout( hideTooltip, hideTime*1000 )
    }

  }

  function hideTooltip() {

    if ( hideAnim ) {
      hideAnim.run();
      return;
    }

    if ( hideTimer ) { clearTimeout(hideTimer); }

    tooltip.style.display = 'none';

    if ( hiddenTime && hiddenTime > 0 ) {
	//opera.debug('Delaying');
      stayHidden = true;
      hiddenTimer = setTimeout( 
        function () { 
          stayHidden = false; 
          //opera.debug('Showing after hide');
          clearTimeout(hiddenTimer); 
          showTooltip();
        }, 
        hiddenTime * 1000 
      );
    }

  }

}


/**
 * Find the X position of an object, relative to the viewport
 * Code copied from quirksmode.org
 * @param obj  Object to find x position for
 */
function findPosX(obj)
{
	var curleft = 0;
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curleft += obj.offsetLeft
			obj = obj.offsetParent;
		}
	}
	else if (obj.x)
		curleft += obj.x;
	return curleft;
}

/**
 * Find the Y position of an object, relative to the viewport
 * Code copied from quirksmode.org
 * @param obj  Object to find y position for
 */
function findPosY(obj)
{
	var curtop = 0;
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curtop += obj.offsetTop
			obj = obj.offsetParent;
		}
	}
	else if (obj.y)
		curtop += obj.y;
	return curtop;
}
