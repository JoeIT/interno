<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     html_link
 * Purpose:  Easy use of HTML links in templates
 * Use:     if page is present without an '.php' or '.html' extension add '.php' else, use the given page
            if no page is present but there's an url present, use that.
            if no text is present use url as text
            - ability to add target, class, title and onClick to the HTML tag
            - when external is set to 'yes' and no target is present then default target is '_blank', else
              use target

            Every other given option will be used as parameter to extend the URL (like ?id=1&print=1 )
 * Version:  1.0
 * Date:     May 5, 2003
 * Install:  Drop into the plugin directory
 * Author:   Patrick van Dissel <robert10 at chello dot nl>
 * -------------------------------------------------------------
 * Original Name: ahref
 * Original Author:   Peter Dudas <duda at bigfish dot hu>
 *
 */
function smarty_function_html_link($params, &$smarty)
{

/*
Parameters
    page      - name of php page to link to (without extension) **only for internal pages!!**
    url       - href (simple 'ftp://', http://', 'mailto:'  is regarded as empty)
    target    - <a target=""
    class     - <a class=""
    onClick   - <a onClick=""

    Every other parameter is used as extra parameter for the url
*/

    extract($params);
    if (empty($params)) {
        return;
    }

    //All usable options
    $options = array('page'     => '',
                     'url'      => '',
                     'target'   => '',
                     'text'     => '',
                     'title'    => '',
                     'class'    => '',
                     'onClick'  => '',
                     'external' => '');

    //Walk through the given parameters
    //and filter out all parameters which
    //are not usable options
    $not_params = array();
    foreach ($params as $key => $value) {
        if (!array_key_exists($key, $options)) {
            $not_params[$key] = $value;
        }
    }

    //check per usable option if it is set and has a value
    if (isset($page) && !empty($page) &&
        (!preg_match('#(.php|.html)$#', $page))) {
            $url = $page.'.php';
    } elseif (($external == 'yes') AND !preg_match('#^([htf]+p://)|(mailto:)$#', $url)) {
        $url = 'http://'.$url;
    } elseif (!empty($url))   {
        // if url is email, add 'mailto:' before it
        if (!preg_match('#^([htf]+p://)|(mailto:)|(/)#', $url)) {
            if (ereg('@',$url)) {
                $url = 'mailto:'.$url;
            } elseif ($external == TRUE) {
                $url = 'http://'.$url;
            }
        }
    }

    if (!empty($not_params)) {
        $i = 0;
        foreach ($not_params as $key => $value) {
            if ($i == 0) {
                $splitstr = '?';
            } else {
                $splitstr = '&';
            }
            $vars .= $splitstr.$key.'='.$value;
            $i++;
        }
    }

    if (!empty($class))   {
        $extra .= ($extra ? ' ' : '').' class="'.$class.'"';
    }
    if (!empty($onClick)) {
        $extra .= ($extra ? ' ' : '').'onClick="'.$onClick.'"';
    }
    if (empty($target) && $external == 'yes')   {
        $extra .= ($extra ? ' ' : '').'target="_blank"';
    } elseif (!empty($target)) {
        $extra .= ($extra ? ' ' : '').'target="'.$target.'"';
    }
    if (!empty($title))   {
        $extra .= ($extra ? ' ' : '').'title="'.$title.'"';
    }

    //if the text option is not given, use the url as text
    if (empty($text))   {
        $text = $url;
    }

    //Output the html tag with every option on it's plate :)
    $output = '<a href="'.trim($url).($vars ? ''.$vars : '').'"'.($extra ? ''.$extra : '').'>'.$text.'</a>';

    print $output;
}

?>