import { test, expect } from '@playwright/test';

test('La page de la carte se charge correctement', async ({ page }) => {

  await page.goto('http://localhost/map-la/');
  //await expect(page.locator('#id')).toBeVisible();
  const leafletUI = page.getByText('o +−Leaflet');
  await expect(leafletUI).toBeVisible();
  const hedad= page.locator('div').filter({ hasText: 'Zelda: Link\'s Awakening' }).nth(1); 
  await expect(hedad).toBeVisible(); 
  await page.getByRole('button', { name: 'icon-user' }).click();
  await page.getByRole('textbox', { name: 'Email' }).fill('ahouandogboamen@gmail.com');
  await page.getByRole('textbox', { name: 'Password' }).fill('root'); 
  await page.getByRole('button', { name: 'Login' }).click(); 
   await page.pause(); 
});
