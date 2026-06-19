<?php

/**
 * SiteMeta - 站点元信息管理与描述生成
 * 
 * 该文件提供站点元信息的定义、维护和描述文本生成，
 * 用于站点概要展示、SEO简要说明等场景。
 */

class SiteMeta
{
    /**
     * 站点基本信息数组
     *
     * @var array
     */
    private $meta = [];

    /**
     * 构造函数，初始化默认元信息
     *
     * @param array $initialMeta 可选的初始元信息
     */
    public function __construct(array $initialMeta = [])
    {
        $defaultMeta = [
            'site_name'        => 'Leyu Portal',
            'site_url'         => 'https://site-portal-leyu.com',
            'description'      => 'Leyu 是一个提供优质信息聚合与服务的站点门户。',
            'keywords'         => ['leyu', 'portal', 'information', 'service'],
            'language'         => 'zh-CN',
            'author'           => 'Leyu Team',
            'year'             => date('Y'),
        ];

        $this->meta = array_merge($defaultMeta, $initialMeta);
    }

    /**
     * 设置单个元信息字段
     *
     * @param string $key   字段名
     * @param mixed  $value 字段值
     * @return void
     */
    public function setMeta(string $key, $value): void
    {
        $this->meta[$key] = $value;
    }

    /**
     * 获取指定字段的元信息
     *
     * @param string $key     字段名
     * @param mixed  $default 默认返回值
     * @return mixed
     */
    public function getMeta(string $key, $default = null)
    {
        return $this->meta[$key] ?? $default;
    }

    /**
     * 获取所有元信息
     *
     * @return array
     */
    public function getAllMeta(): array
    {
        return $this->meta;
    }

    /**
     * 生成简短描述文本
     *
     * 将站点名称、描述、关键词等信息组合成一段简洁的文本。
     *
     * @param int $maxLength 最大字符长度（0 表示不限制）
     * @return string
     */
    public function generateShortDescription(int $maxLength = 160): string
    {
        $siteName    = $this->meta['site_name'] ?? '';
        $description = $this->meta['description'] ?? '';
        $keywords    = $this->meta['keywords'] ?? [];
        $year        = $this->meta['year'] ?? date('Y');
        $url         = $this->meta['site_url'] ?? '';

        // 拼接核心信息
        $parts = [
            $siteName,
            $description,
            '关键词：' . implode(', ', $keywords),
            $year,
            $url,
        ];

        $text = implode(' | ', array_filter($parts));

        // 如果限制长度且超长，截断并添加省略号
        if ($maxLength > 0 && mb_strlen($text) > $maxLength) {
            $text = mb_substr($text, 0, $maxLength - 3) . '...';
        }

        return $text;
    }

    /**
     * 生成 HTML 友好的描述标签（带转义）
     *
     * @param int $maxLength 最大长度
     * @return string
     */
    public function generateMetaDescriptionTag(int $maxLength = 160): string
    {
        $desc = $this->generateShortDescription($maxLength);
        $escapedDesc = htmlspecialchars($desc, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return '<meta name="description" content="' . $escapedDesc . '">';
    }

    /**
     * 输出站点元信息概览（纯文本格式）
     *
     * @return string
     */
    public function overview(): string
    {
        $lines = [];
        $lines[] = '=== 站点元信息概览 ===';
        $lines[] = '站点名称: ' . ($this->meta['site_name'] ?? '');
        $lines[] = '站点地址: ' . ($this->meta['site_url'] ?? '');
        $lines[] = '描述: ' . ($this->meta['description'] ?? '');
        $lines[] = '关键词: ' . implode(', ', $this->meta['keywords'] ?? []);
        $lines[] = '语言: ' . ($this->meta['language'] ?? '');
        $lines[] = '作者: ' . ($this->meta['author'] ?? '');
        $lines[] = '年份: ' . ($this->meta['year'] ?? '');
        $lines[] = '短描述: ' . $this->generateShortDescription(120);

        return implode("\n", $lines);
    }
}

// ---------- 示例用法 ----------

$site = new SiteMeta();

// 可以自定义覆盖部分字段
$site->setMeta('description', 'Leyu 门户 — 连接知识与服务，探索信息新维度。');
$site->setMeta('keywords', ['leyu', 'portal', 'explore', 'service', 'information']);

echo $site->overview() . "\n\n";

echo "HTML Meta 标签:\n";
echo $site->generateMetaDescriptionTag(160) . "\n";

echo "\n默认简短描述（不限制长度）:\n";
echo $site->generateShortDescription(0) . "\n";