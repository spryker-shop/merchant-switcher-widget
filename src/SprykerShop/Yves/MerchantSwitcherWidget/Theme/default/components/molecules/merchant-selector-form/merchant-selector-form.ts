import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import { error } from 'ShopUi/app/logger';

export default class MerchantSelectorForm extends Component {
    protected ajaxProvider: AjaxProvider;
    protected form: HTMLFormElement;
    protected select: HTMLSelectElement;
    protected message: string;
    protected initiallySelectedIndex: number;

    protected readyCallback(): void {}

    protected init(): void {
        this.ajaxProvider = <AjaxProvider>this.getElementsByClassName(`${this.jsName}__provider`)[0];
        this.form = <HTMLFormElement>this.getElementsByClassName(`${this.jsName}__form`)[0];
        this.select = <HTMLSelectElement>this.form.getElementsByClassName(`${this.jsName}__select`)[0];
        this.initiallySelectedIndex = this.select.selectedIndex;
        this.message = this.messageTemplate;
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.select.addEventListener('change', () => this.onChangeHandler());
    }

    protected onChangeHandler(): void {
        this.updateMessageText();
        this.setMerchant();
    }

    /**
     * Updates a text of the confirmation question.
     */
    updateMessageText(): void {
        const currentMerchantOptionText: string = this.select.options[this.initiallySelectedIndex].text;
        const newMerchantOptionText: string = this.select.options[this.select.selectedIndex].text;

        this.message = this.messageTemplate.replace(this.currentMerchantNameTemplate, currentMerchantOptionText);
        this.message = this.message.replace(this.newMerchantNameTemplate, newMerchantOptionText);
    }

    /**
     * Sets merchant after according answer of confirmation question.
     */
    async setMerchant(): Promise<void> {
        if (!confirm(this.message)) {
            return;
        }

        const data = new FormData(this.form);
        const appendData = { 'merchant_switcher_selector_form[_token]': this.csrfToken };

        Object.keys(appendData).forEach((key: string) => data.append(key, appendData[key]));

        try {
            await this.ajaxProvider.fetch(data);
            document.location.reload();
        } catch (e) {
            error(e);
        }
    }

    protected get messageTemplate(): string {
        return this.getAttribute('message-template');
    }

    protected get currentMerchantNameTemplate(): string {
        return this.getAttribute('current-merchant-name-template');
    }

    protected get newMerchantNameTemplate(): string {
        return this.getAttribute('new-merchant-name-template');
    }

    protected get csrfToken(): string {
        return this.getAttribute('csrf-token');
    }
}
