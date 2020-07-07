import { Page, Browser, BrowserContext } from 'playwright';
import 'expect-playwright';

declare global {
	const browserName: string;
	const deviceName: string | null;
	const page: Page;
	const browser: Browser;
	const context: BrowserContext;
}
