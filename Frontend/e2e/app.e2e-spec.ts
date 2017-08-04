import { UserRegistrationPage } from './app.po';

describe('user-registration App', () => {
  let page: UserRegistrationPage;

  beforeEach(() => {
    page = new UserRegistrationPage();
  });

  it('should display welcome message', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('Welcome to app!!');
  });
});
