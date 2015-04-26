<?php

/*
 * Parser Class extended with Helper
 */

class Parser extends Helper {

    public function getFeed($url) {

        global $config;

        if(isset($url)) {

                $options = array(
                    CURLOPT_RETURNTRANSFER => true      // Server will send back response to client
                );

                $curl = curl_init($url);
                curl_setopt_array($curl, $options);
                $content = curl_exec($curl);
                curl_close($curl);

                return $content;                        // Return response
        }

    }

    public function parse($url) {

        global $config;

        // XML Raw contents
        $raw_contents = $this->getFeed($url);

        if(isset($raw_contents) && strlen($raw_contents) > 0) {

            // Remove unwanted data specially (additional) details in my case
            $raw_contents = $this->cleanData('additional', $raw_contents, true);

            // Parsed all products in array
            $products = $this->fetchData('product', $raw_contents, true, true);

            // Process individual product and extract required data to build template for data file
            $counter = 1;
            $success = false;

            foreach ($products as $product) {
                $category = '';
                $price = '';
                $currency = '';
                $data_template = $this->dataFileTemplate();

                // PRODUCT_ID
                $data_template = str_replace('%%PRODUCT_ID%%', $this->fetchData('productID', $product, true, false), $data_template);

                // PRODUCT_NAME
                $data_template = str_replace('%%PRODUCT_NAME%%', $this->fetchData('name', $product, true, false), $data_template);

                // PRODUCT_DESCRIPTION
                $desc = $this->fetchData('description', $product, true, false);
                $desc = str_replace(array('<![CDATA[', ']]>'), '', $desc);
                $data_template = str_replace('%%PRODUCT_DESCRIPTION%%', $desc, $data_template);

                // PRODUCT_PRICE
                $price = $this->fetchData('price', $product, true, false);
                $data_template = str_replace('%%PRODUCT_PRICE%%', $price, $data_template);

                // PRODUCT_CURRENCY
                $currency = $this->fetchAttribute('price', $product);
                $data_template = str_replace('%%PRODUCT_CURRENCY%%', $currency['currency'], $data_template);

                // PRODUCT_CATEGORY
                $categories = $this->fetchData('categories', $product, true, true);
                foreach ($categories as $cat) {
                    $category_attr = $this->fetchAttribute('category', $cat);
                    $category .= $category_attr['path'] . ' , ';
                }
                $category = substr($category, 0, strlen($category) - 2);
                $data_template = str_replace('%%PRODUCT_CATEGORY%%', $category, $data_template);

                // PRODUCT_URL
                $data_template = str_replace('%%PRODUCT_URL%%', $this->fetchData('productURL', $product, true, false), $data_template);

                $this->generateFile($data_template, $counter);
                $counter++;
                $success = true;
            }

            if($success)
                $this->redirectTo($config['redirect_home'], 'success', "( ".$this->getSession('products')." ) "." products have parsed!" );

        } else {
            $this->redirectTo($config['redirect_home'], 'error', "Invalid Feed URL" );
        }
    }

    public function fetchAttribute($ele_name, $xml_content) {

        if ($xml_content == false) {
            return false;
        }

        // Match element and extract it's attributes
        $match = preg_match('#<'.$ele_name.'\s+([^>]+(?:"|\'))\s?/?>#',$xml_content, $matches);

        if ($match == 1) {
            $attribute_array = array();
            $attribute_string = $matches[1];

            $match_attr = preg_match_all('#([^\s=]+)\s*=\s*(\'[^<\']*\'|"[^<"]*")#',$attribute_string, $matches, PREG_SET_ORDER);

            if ($match_attr != 0) {

                // Create an array with matched attributes
                foreach ($matches as $attribute) {
                    $attribute_array[$attribute[1]] =
                        substr($attribute[2], 1, -1);
                }

                return $attribute_array;
            }
        }

        return false;
    }

    public function fetchData($ele_name, $xml_content, $with_parent_element = true, $is_match_all = false) {
        if ($xml_content == false) {
            return false;
        }

        if($is_match_all) {
            $match = preg_match_all('#<' . $ele_name . '(?:\s+[^>]+)?>(.*?)' .
                '</' . $ele_name . '>#s', $xml_content, $matches, PREG_PATTERN_ORDER);
        } else {
            $match = preg_match('#<' . $ele_name . '(?:\s+[^>]+)?>(.*?)' .
                '</' . $ele_name . '>#s', $xml_content, $matches);
        }

        if ($match != false) {
            if ($with_parent_element) {
                return $matches[1];
            } else {
                return $matches[0];
            }
        }
        // No match found: return false.
        return false;
    }

    public function cleanData($ele_name, $xml_content, $is_match_all = false) {
        if ($xml_content == false) {
            return false;
        }

        if($is_match_all)
            $match = preg_match_all('#<'.$ele_name.'(?:\s+[^>]+)?>.*?'.
                '</'.$ele_name.'>#s', $xml_content, $matches, PREG_PATTERN_ORDER);
        else
            $match = preg_match('#<'.$ele_name.'(?:\s+[^>]+)?>.*?'.
                '</'.$ele_name.'>#s', $xml_content, $matches);

        if ($match != false) {
            if(count($matches) > 0) {
                $data = '';
                foreach($matches as $match) {
                    $data .= str_replace($match,'', $xml_content)."\n";
                }
            }
            return $data;
        }

        return false;
    }

    public function generateFile($contents, $counter) {
        global $config;
        if(isset($contents) && $contents != '') {

            file_put_contents($config['ftp_data'] . '/' . $config['prefix_datafile'] . $counter . '.txt', $contents);

            // Setting products counter in session for messages
            $this->setSession('products', $counter);

        }
    }

    public function dataFileTemplate() {
        $template = "Product:\t%%PRODUCT_NAME%%\t(%%PRODUCT_ID%%)\n\rDescription:\t%%PRODUCT_DESCRIPTION%%\nPrice:\t\t%%PRODUCT_CURRENCY%% %%PRODUCT_PRICE%%\nCategories:\t%%PRODUCT_CATEGORY%%\nURL:\t\t%%PRODUCT_URL%%";
        return $template;
    }
}