var LoginPage = require('../pages/login.page.js');
describe("remove_build", function() {
  it("remove build", function() {
    var loginPage = new LoginPage();
    loginPage.login();
    browser.get('index.php?project=InsightExample');

    // Locate the folder icon for the 2nd build.
    var folderIcon = element(by.repeater('build in buildgroup.pagination.filteredBuilds').row(1)).all(by.tagName('img')).get(1);

    // Make sure that we located the right img.
    expect(folderIcon.getAttribute('src')).toContain('images/folder.png');

    // Click the icon to expand the menu.
    folderIcon.click();

    // Find the 'remove this build' link and click it.
    var link = element(by.partialLinkText('remove this build'));
    link.click();
    browser.waitForAngular();

    // This generates a confirmation dialog which we have to accept.
    // Wait for it to appear.
    browser.wait(function() {
      return browser.switchTo().alert().then(
        function() { return true; },
        function() { return false; }
      );
    }, opt_timeout=1000);

    // Then switch to it & click on it.
    var alertDialog = browser.switchTo().alert();
    alertDialog.accept();
    browser.waitForAngular();

    // Refresh the page to make sure this build is gone now.
    browser.get('index.php?project=InsightExample');
    expect(element.all(by.repeater('build in buildgroup.pagination.filteredBuilds')).count()).toBe(5);
  });
});