<?php
 
class np6_mailPerformance_Model_Observer
{
   public function changeSystemConfig(Varien_Event_Observer $observer)
    {
        //get init sections and tabs
        $config = $observer->getConfig();
 
        //get tab 'advanced', change sort order and label
        $advancedTab = $config->getNode('tabs/mailPerformance_tab');
        $advancedTab->sort_order = 2;
        $advancedTab->label .= ' (test)';

        $adminSectionGroups = $config->getNode('sections/mailPerformance_dataBinding_section/groups');
 
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
                            <show_in_website>0</show_in_website>
                            <show_in_store>1</show_in_store>
                        </id_custormer_field>                      
                        <firstname_field translate="">
                            <label>First name</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_combo_AllText</source_model>
                            <sort_order>2</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>0</show_in_website>
                            <show_in_store>1</show_in_store>
                        </firstname_field>
                         <lastname_field translate="">
                            <label>Last name</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_combo_AllText</source_model>
                            <sort_order>2</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>0</show_in_website>
                            <show_in_store>1</show_in_store>
                        </lastname_field>
                        <gender_field translate="">
                            <label>Gender</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_combo_allList</source_model>
                            <sort_order>3</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>0</show_in_website>
                            <show_in_store>1</show_in_store>
                        </gender_field>
                         <gender_Md_field translate="">
                            <label>Woman</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboDV</frontend_model>
                            <source_model>mailPerformance/system_config_source_gender</source_model>
                            <sort_order>4</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>0</show_in_website>
                            <show_in_store>1</show_in_store>
                        </gender_Md_field>
                        <gender_Mr_field translate="">
                            <label>Man</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboDV</frontend_model>
                            <source_model>mailPerformance/system_config_source_gender</source_model>
                            <sort_order>4</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>0</show_in_website>
                            <show_in_store>1</show_in_store>
                        </gender_Mr_field>
                         <email_field translate="">
                            <label>email</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_email</source_model>
                            <sort_order>5</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>0</show_in_website>
                            <show_in_store>1</show_in_store>
                        </email_field>
                        <birthday_field translate="">
                            <label>Birthday</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_date</source_model>
                            <sort_order>6</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>0</show_in_website>
                            <show_in_store>1</show_in_store>
                        </birthday_field>
                         <suscriptionDate_field translate="">
                            <label>Newsletters suscription date</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_date</source_model>
                            <sort_order>6</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>0</show_in_website>
                            <show_in_store>1</show_in_store>
                        </suscriptionDate_field>
                        <optin_field translate="">
                            <label>Third party offers</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboBinding</frontend_model>
                            <source_model>mailPerformance/system_config_source_combo_allList</source_model>
                            <sort_order>7</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>0</show_in_website>
                            <show_in_store>1</show_in_store>
                        </optin_field>
                        <optin_no_field translate="">
                            <label>no</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboDV</frontend_model>
                            <source_model>mailPerformance/system_config_source_optin</source_model>
                            <sort_order>8</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>0</show_in_website>
                            <show_in_store>1</show_in_store>
                        </optin_no_field>
                        <optin_yes_field translate="">
                            <label>yes</label>
                            <frontend_model>mailPerformance/adminhtml_system_config_comboDV</frontend_model>
                            <source_model>mailPerformance/system_config_source_optin</source_model>
                            <sort_order>8</sort_order>
                            <show_in_default>1</show_in_default>
                            <show_in_website>0</show_in_website>
                            <show_in_store>1</show_in_store>
                        </optin_yes_field>
                    </fields>
                </DataBinding_group>
            ');

            $adminSectionGroups->appendChild($dataBinding_group_xml);

        }
 
        return $this;
    }
}
