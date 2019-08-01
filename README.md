# 開發日誌

## TODO

---

- [ ] 使用`bootstrap` 的 `Grid system`
- [ ] `ProfileController` 只有找 `ggg_ladder_history` 的資料，要再補齊
- [ ] 個人資料頁
  - [ ] 排名變化表

---

## 20190801

- [x] 改善搜尋功能
- [x] 改善 `pagination`
- [x] 改善聯盟切換鈕
- [x] `navbar` 滑動時自動關閉
- [x] `Ladder` 頁面增加上線顯示
- [x] 使用 `github` 做版本控制

## 20190731

- [x] 上線時間表

## 20190730

- [x] 解決時區問題

## 20190729

- [x] [解決 `distinct` 和 `order by` 衝突](https://blog.csdn.net/dipolar/article/details/22170305)
- [x] 解決同帳號重複角色名稱問題，改以 `character_id` 辨識
- [x] 改用 `Google Charts`
- [x] 上線時間表完成一半， `ssf` 還沒完成

## 20190728

- [x] 使用 `chartJS` 繪圖
- [https://stackoverflow.com/questions/51918932/how-to-properly-include-a-library-from-node-modules-into-your-project](https://stackoverflow.com/questions/51918932/how-to-properly-include-a-library-from-node-modules-into-your-project)

## 20190728

- [x] 新增搜尋功能
- [x] 開始製作個人資料頁面
- [https://learnku.com/articles/6067/5-methods-for-laravel-to-obtain-routing-parameters-route-parameters](https://learnku.com/articles/6067/5-methods-for-laravel-to-obtain-routing-parameters-route-parameters)

## 20190727

- [x] [改寫 `GuzzleHttp\Pool` 多線程](https://gist.github.com/sikofitt/f78bec648abb1928e4c085548a947f9a)
- [x] 開發環境轉移到 MacOS

## 20190726

- [x] 發現 `ssf` 和 `non-ssf` 會重疊角色，資料庫重新定義

## 20190725

- [x] 關連式資料庫
- [x] ISO-8601
- [x] 選擇最新的資料

```sql
SELECT cached_since,rank,gid.character_name,cached_since
FROM ggg_ladder_history ghi, ggg_ladder_id gid
WHERE gid.character_name = ghi.character_name
and league='ggg_tmpssfhc'
and cached_since=(select max(cached_since) from ggg_ladder_history)
group by rank,gid.character_name,cached_since
order by rank
```

## 20190724

- [x] `pagination` 改成下拉式選單
- [x] [`RWD` 隱藏 row](https://stackoverflow.com/questions/38208901/bootstrap-table-hide-column-in-mobile-view)

## 20190723

- [x] table 分頁功能
- [x] 使用 `jquery-loading` 在需要更新頁面時鎖定畫面
- [x] [`pagination`太多時避免超出畫面](https://stackoverflow.com/questions/39387614/make-bootstrap-pagination-overflow-horizontally)
- [x] 增加 `-webkit-appearance: none;`

## 20190722

- [x] 完成資料庫 table 切換

## 20190720

- 考慮重構成 `SPA`
- [https://medium.com/@hulitw/introduction-mvc-spa-and-ssr-545c941669e9](https://medium.com/@hulitw/introduction-mvc-spa-and-ssr-545c941669e9)

## 20190719

- [x] 在 PostgreSQL 內儲存 `jsonb` 類型資料 [PostgreSQL json 操作](https://medium.com/@sj82516/postgresql-json-%E6%93%8D%E4%BD%9C-76ba596faab3)
- [x] 避免重複抓取資料
- [x] 使用 `GuzzleHttp\Pool` 做多線程 get
- [x] 在 `testpage` 內排序資料
- [x] [呼叫 `Coltroller` 內部 function](https://stackoverflow.com/questions/30365169/access-controller-method-from-another-controller-in-laravel-5/30365349#30365349)

### 20190718

- [x] 資料收集到資料庫

#### tags: `Laravel` `POE`
