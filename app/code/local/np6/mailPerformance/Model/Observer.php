<?php
 
class np6_mailPerformance_Model_Observer
{

    //event // 

   public function changeSystemConfig(Varien_Event_Observer $observer)
    {
        //get init sections and tabs
        $config = $observer->getConfig();
 
        //get tab 'advanced', change sort order and label
        $advancedTab = $config->getNode('tabs/mailPerformance_tab');
        $advancedTab->sort_order = 2;
        $advancedTab->label .= ' (test)';

        $adminSectionGroups = $config->getNode('sections/mailPerformance_dataBinding_section/groups');
        $adminActionSectionGroups = $config->getNode('sections/mailPerformance_action_section/groups');
 
        if(Mage::getSingleton('mailPerformance/api')->isConnected())
        {
            //ajout de la partie databinding si connecter
            $dataBinding_group_xml = new Mage_Core_Model_Config_Element('
            <DataBinding_group translate="">
                    <label>Link Magento to MailPerformance</label>
                    <comment></comment>
                    <sort_order>2</sort_order>
                    <show_in_default>1</show_in_default>
                    <show_in_website>1</show_in_website>
                    <show_in_store>1</show_in_store>
                    <fields>
                        <id_custormer_field translate="">
                            <label>Id</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <backend_model>mailPerformance/system_config_backend_dataBinding</backend_model>
                            <source_model>mailPerformance/system_config_source_numeric</source_model>
                            <sort_order>1</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </id_custormer_field>                      
                        <firstname_field translate="">
                            <label>First name</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_combo_AllText</source_model>
                            <sort_order>2</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </firstname_field>
                         <lastname_field translate="">
                            <label>Last name</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_combo_AllText</source_model>
                            <sort_order>2</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </lastname_field>
                        <gender_field translate="">
                            <label>Gender</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_combo_allList</source_model>
                            <sort_order>3</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </gender_field>
                         <gender_Md_field translate="">
                            <label>Woman</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboDV</frontend_model>
                            <source_model>mailPerformance/system_config_source_gender</source_model>
                            <sort_order>4</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </gender_Md_field>
                        <gender_Mr_field translate="">
                            <label>Man</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboDV</frontend_model>
                            <source_model>mailPerformance/system_config_source_gender</source_model>
                            <sort_order>4</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </gender_Mr_field>
                         <email_field translate="">
                            <label>email</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_email</source_model>
                            <sort_order>5</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </email_field>
                        <birthday_field translate="">
                            <label>Birthday</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_date</source_model>
                            <sort_order>6</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </birthday_field>
                    </fields>
                </DataBinding_group>
            ');


             $userLink_group_xml = new Mage_Core_Model_Config_Element('
                <UserLink_group translate="">
                    <label>User Inscription</label>
                    <sort_order>3</sort_order>
                    <show_in_default>1</show_in_default>
                    <show_in_website>1</show_in_website>
                    <show_in_store>1</show_in_store>
                    <fields>
                         <User_AutoAdd_field translate="">
                            <label>Add User at inscription ?</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_radioButon</frontend_model>
                            <source_model>mailPerformance/system_config_source_userAdd</source_model>
                            <sort_order>1</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </User_AutoAdd_field> 
                        <segment_field translate="">
                            <label>Add on segment</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_segment</source_model>
                            <comment><![CDATA[<a target="_blank" href="../mailPerformance_action_section">Need to add a segment ?</a>]]></comment>
                            <sort_order>3</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </segment_field>
                    </fields>
                </UserLink_group>
            ');

            $UpdateCart_group_xml = new Mage_Core_Model_Config_Element('
                 <Event_UpdateCart_group translate="">
                    <label>Event : Cart creation or update</label>
                    <sort_order>4</sort_order>
                    <show_in_default>1</show_in_default>
                    <show_in_website>1</show_in_website>
                    <show_in_store>1</show_in_store>
                    <fields>
                        <segment_field>
                            <label>Segment MailPerformance</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_segment</source_model>
                            <comment><![CDATA[<a target="_blank" href="../mailPerformance_action_section">Need to add a segment ?</a>]]></comment>
                            <sort_order>1</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </segment_field>
                        <dateLastModif_field>
                            <label>Cart last modification date :</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_date</source_model>
                            <sort_order>2</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </dateLastModif_field>
                        <cartItems_field>
                            <label>Number of item in cart :</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_numeric</source_model>
                            <sort_order>3</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </cartItems_field>
                        <cartPrice_field>
                            <label>Cart price :</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_combo_AllText</source_model>
                            <sort_order>4</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </cartPrice_field>
                    </fields>
                </Event_UpdateCart_group>
            ');

           
            $ExportCSV_group_xml = new Mage_Core_Model_Config_Element('
                <ExportCSV_group translate="">
                    <label>Export CSV</label>
                    <sort_order>40</sort_order>
                    <show_in_default>1</show_in_default>
                    <show_in_website>1</show_in_website>
                    <show_in_store>1</show_in_store>
                    <fields>
                         <csv_field translate="">
                            <label>Export des target en csv</label>
                            <button_label>Export</button_label>
                            <frontend_model>mailPerformance/adminhtml_system_config_exportCSV</frontend_model>
                            <sort_order>1</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </csv_field> 
                    </fields>
                </ExportCSV_group>
            ');

            $AddSegment_group_xml = new Mage_Core_Model_Config_Element('
                <AddSegment_group translate="">
                    <label>Add Segment</label>
                    <sort_order>50</sort_order>
                    <show_in_default>1</show_in_default>
                    <show_in_website>1</show_in_website>
                    <show_in_store>1</show_in_store>
                    <fields>
                         <name_field translate="">
                            <label>Segment name</label>
                            <backend_model>mailPerformance/system_config_backend_addSegment</backend_model>
                            <sort_order>1</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </name_field> 
                         <description_field translate="">
                            <label>Description</label>
                            <backend_model>mailPerformance/system_config_backend_noSave</backend_model>
                            <sort_order>2</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </description_field> 
                        <expirationDate_field translate="">
                            <label>Expiration date</label>
                            <frontend_type>Text</frontend_type>
                            <frontend_model>mailPerformance/adminhtml_system_config_date</frontend_model>
                            <backend_model>mailPerformance/system_config_backend_noSave</backend_model>
                            <sort_order>3</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </expirationDate_field>
                         <save_field translate="">
                            <label></label>
                            <button_label>Sauvegarder</button_label>
                            <frontend_model>mailPerformance/adminhtml_system_config_buttonSave</frontend_model>
                            <sort_order>10</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>1</show_in_website>
                            <show_in_store>1</show_in_store>
                        </save_field>  
                    </fields>
                </AddSegment_group>
            ');

            //Section Configuration
            $adminSectionGroups->appendChild($dataBinding_group_xml);
            $adminSectionGroups->appendChild($userLink_group_xml);
            $adminSectionGroups->appendChild($UpdateCart_group_xml);
            //Section Action
            $adminActionSectionGroups->appendChild($ExportCSV_group_xml);
            $adminActionSectionGroups->appendChild($AddSegment_group_xml);

        }
 
        return $this;
    }

    public function customerRegisterSuccess(Varien_Event_Observer $observer) {
       
        Mage::log((new DateTime())->format('Y-m-d H:i:s')." CustomerRegister hook Start");

        $event = $observer->getEvent();
        $customer = $event->getCustomer();


        $id = $customer->getId();
        $firstname = $customer->getFirstname(); 
        $lastname = $customer->getLastname();
        $email = $customer->getEmail();
        $gender = $customer->getGender();
        $DateOfBirth = $customer->getDob();
        $creationDate = $customer->getCreatedAtTimestamp();


        //suscriber status
        $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
        $isSubscribed = false;
        if($subscriber->getId())
        {
            $isSubscribed = $subscriber->getData('subscriber_status') == Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED;
        }

        //segment status
        $id_segment = null;
        $segment = Mage::getStoreConfig('mailPerformance_dataBinding_section/UserLink_group/segment_field');
        if($segment != 0 && $segment != -1)
        {
            $id_segment = $segment;
        }


        $User_AutoAdd = Mage::getStoreConfig('mailPerformance_dataBinding_section/UserLink_group/User_AutoAdd_field');
        if( $User_AutoAdd == 0)
        {
            Mage::log((new DateTime())->format('Y-m-d H:i:s')." No add");
            return;
        }
        else if ($User_AutoAdd == 1 && $isSubscribed)
        {
            //only if suscriber
            Mage::log((new DateTime())->format('Y-m-d H:i:s')." Add target only because newsletter");
            
            $targetinformation = $this->CreateArrayTarget($id, $firstname, $lastname, $email, $gender, $DateOfBirth);

            Mage::getSingleton('mailPerformance/api')->CreateNewTarget($targetinformation, $id, $id_segment); 
        
        }
        else if ($User_AutoAdd == 2)
        {
            //everyone is add
            Mage::log((new DateTime())->format('Y-m-d H:i:s')." Add target");
            
           $targetinformation = $this->CreateArrayTarget($id, $firstname, $lastname, $email, $gender, $DateOfBirth);

            Mage::getSingleton('mailPerformance/api')->CreateNewTarget($targetinformation, $id, $id_segment); 
        }
        else
        {
            Mage::log((new DateTime())->format('Y-m-d H:i:s')." no add beacause no newsletter");
        }

         Mage::log((new DateTime())->format('Y-m-d H:i:s')." CustomerRegister hook End");
}

    public function customerSaveBefore(Varien_Event_Observer $observer)
    {
       
       // event beging
        $event = $observer->getEvent();
        $customer = $event->getCustomer();

        Mage::log("Customer Update, id = ".$customer->getId());

        //Get the table who link user mp to magento
        $UserLink = Mage::getModel('mailPerformance/mailPerformance')->load($customer->getId());
        $Id_UserMP = $UserLink->getData('id_mailperf');


        if($Id_UserMP != null && $Id_UserMP != '')
        {
            //do maj of user

            $targetinformation = $this->CreateArrayTarget($customer->getId(), $customer->getFirstname(), $customer->getLastname(), $customer->getEmail(), $customer->getGender(), $customer->getDob());

            Mage::getSingleton('mailPerformance/api')->UpdateTarget($targetinformation, $Id_UserMP); 

        }
        else
        {
            // User not link now to mp 
            return;
        }
    }

    public function HookCartSave(Varien_Event_Observer $observer)
    {
        Mage::log("HookCartSave Is starting !!!");

        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $order = Mage::getModel('sales/order');

         Mage::log($order);

        if($customer != null)
        {
            //Get the table who link user mp to magento
            $UserLink = Mage::getModel('mailPerformance/mailPerformance')->load($customer->getId());
            $Id_UserMP = $UserLink->getData('id_mailperf');

            if($Id_UserMP != null && $Id_UserMP != '')
            {
                //user allready exist we juste need to update him

                $targetinformation = $this->CreateArrayTarget($customer->getId(), $customer->getFirstname(), $customer->getLastname(), $customer->getEmail(), $customer->getGender(), $customer->getDob());
                Mage::log($targetinformation);
                $targetinformation[Mage::getStoreConfig('mailPerformance_dataBinding_section/Event_UpdateCart_group/dateLastModif_field')] = Date('YYYY-mm-dd');
                //$targetinformation[Mage::getStoreConfig('mailPerformance_dataBinding_section/Event_UpdateCart_group/cartItems_field')] = new Date();
                //$targetinformation[Mage::getStoreConfig('mailPerformance_dataBinding_section/Event_UpdateCart_group/cartPrice_field')] = new Date();

                Mage::log($targetinformation);

                //Mage::getSingleton('mailPerformance/api')->UpdateTarget($targetinformation, $Id_UserMP); 

            }
            else
            {
                // User not link now so we need to create it in same time
                
            }
        }
        
    }


    // helper // 

    private function CreateArrayTarget($id, $firstname, $lastname, $email, $gender, $DateOfBirth)
    {
        $targetinformation = array(

                Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/id_custormer_field') => (int) $id,
                Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/firstname_field') =>  $firstname,
                Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/lastname_field') =>  $lastname,
                Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/email_field') =>  $email,
                );


        //dateOfBirth
        if($DateOfBirth != null)
        {
             $targetinformation[Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/birthday_field')] = 'ISODate("'.date('Y-m-d\TH:i:s\Z', strtotime($DateOfBirth)).'")';
        }

        //man
        if($gender == 1)
        {
            $array = Mage::getModel('mailPerformance/System_Config_Source_Gender')->toOptionArray();
            $value = Mage::getModel('mailPerformance/System_Config_Source_Gender')->searchArray($array,Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/gender_Mr_field'));


            $targetinformation[Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/gender_field')] = $value;
        }
        //woman
        else if($gender == 2)
        {
            $array = Mage::getModel('mailPerformance/System_Config_Source_Gender')->toOptionArray();
            $value = Mage::getModel('mailPerformance/System_Config_Source_Gender')->searchArray($array,Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/gender_Md_field'));


            $targetinformation[Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/gender_field')] = $value;
        }

        return $targetinformation;
    }

    

}

