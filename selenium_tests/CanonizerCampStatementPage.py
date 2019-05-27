from CanonizerBase import Page
from Identifiers import CampStatementEditPageIdentifiers,BrowsePageIdentifiers, TopicUpdatePageIdentifiers


class CanonizerCampStatementPage(Page):

    def load_topic_agreement_page(self):
        """
            Go To The topic
        """
        # Browse to Browse Page
        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER).click()

        # Click on Manage/Edit This camp
        self.hover(*CampStatementEditPageIdentifiers.EDIT_CAMP_STATEMENT)
        self.find_element(*CampStatementEditPageIdentifiers.EDIT_CAMP_STATEMENT).click()
        return CanonizerCampStatementPage(self.driver)

    def load_edit_camp_statement_page(self):
        """

        :return:
        """
        self.load_topic_agreement_page()
        # Click on SUBMIT_CAMP_UPDATE_BASED_ON_THIS
        self.hover(*CampStatementEditPageIdentifiers.SUBMIT_STATEMENT_UPDATE_BASED_ON_THIS)
        self.find_element(*CampStatementEditPageIdentifiers.SUBMIT_STATEMENT_UPDATE_BASED_ON_THIS).click()
        return CanonizerCampStatementPage(self.driver)

    def camp_statement_edit_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Register Page Marked with *
        :return: the element value
        """

        return \
            self.find_element(*CampStatementEditPageIdentifiers.NICK_NAME_ASTRK) and \
            self.find_element(*CampStatementEditPageIdentifiers.STATEMENT_ASTRK)

    def enter_nick_name(self, nickname):
        self.find_element(*CampStatementEditPageIdentifiers.NICK_NAME).send_keys(nickname)

    def enter_camp_statement(self, statement):
        self.find_element(*CampStatementEditPageIdentifiers.STATEMENT).send_keys(statement)

    def enter_note(self, note):
        self.find_element(*CampStatementEditPageIdentifiers.NOTE).send_keys(note)

    def click_submit_update_button(self):
        """
        This function clicks the Submit Update Button
        :return:
        """
        self.find_element(*CampStatementEditPageIdentifiers.SUBMIT_UPDATE).click()

    def submit_update(self, nickname, statement, note):
        self.enter_nick_name(nickname)
        self.enter_camp_statement(statement)
        self.enter_note(note)
        self.click_submit_update_button()

    def submit_statement_update_with_blank_nick_name(self, statement, note):
        self.submit_update('', statement, note)
        return self.find_element(*CampStatementEditPageIdentifiers.ERROR_NICK_NAME).text

    def submit_statement_update_with_blank_statement(self, nick_name, note):
        self.submit_update(nick_name, '', note)
        return self.find_element(*CampStatementEditPageIdentifiers.ERROR_STATEMENT).text

    def submit_statement_update_with_valid_data(self, nick_name, statement, note):
        self.submit_update(nick_name, statement, note)
        return self

    def statement_update_page_should_have_add_new_nick_name_link_for_new_users(self):
        return self.find_element(*CampStatementEditPageIdentifiers.ADDNEWNICKNAME).text