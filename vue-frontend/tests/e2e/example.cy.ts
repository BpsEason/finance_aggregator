// vue-frontend/tests/e2e/example.cy.ts
// Cypress E2E Test Example

describe('Basic Front-end Navigation', () => {
  it('應該能夠成功訪問首頁並顯示標題', () => {
    // 訪問前端應用的根路徑
    cy.visit('/')

    // 驗證頁面標題是否存在且內容正確
    cy.get('h1').should('contain', '歡迎來到金融資訊聚合平台！')

    // 驗證導覽列鏈接是否存在
    cy.contains('首頁').should('be.visible')
    cy.contains('股票').should('be.visible')
    cy.contains('匯率').should('be.visible')
    cy.contains('新聞').should('be.visible')
    cy.contains('關於').should('be.visible')
  })

  it('應該能夠導航到股票頁面', () => {
    cy.visit('/')
    cy.contains('股票').click()
    // 驗證 URL 是否變為 /stocks
    cy.url().should('include', '/stocks')
    // 驗證股票頁面標題是否存在
    cy.get('h2').should('contain', '股票列表')
  })

  it('應該能夠導航到匯率頁面', () => {
    cy.visit('/')
    cy.contains('匯率').click()
    cy.url().should('include', '/exchange-rates')
    // 注意: ExchangeRates.vue 元件內部的標題是 H2
    cy.get('h2').should('contain', '匯率列表')
  })

  it('應該能夠導航到新聞頁面', () => {
    cy.visit('/')
    cy.contains('新聞').click()
    cy.url().should('include', '/news')
    cy.get('h2').should('contain', '最新金融新聞')
  })

  it('應該能夠導航到關於頁面', () => {
    cy.visit('/')
    cy.contains('關於').click()
    cy.url().should('include', '/about')
    cy.get('h1').should('contain', '關於本平台')
  })

  # 您可以在這裡添加更多 E2E 測試，例如：
  # - 測試 API 數據是否正確顯示 (需要前端與後端服務都運行)
  # - 測試篩選、排序功能
  # - 測試用戶互動
})
