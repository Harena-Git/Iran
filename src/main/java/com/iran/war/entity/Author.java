package com.iran.war.entity;

import jakarta.persistence.*;
import jakarta.validation.constraints.NotBlank;
import lombok.Getter;
import lombok.Setter;
import lombok.NoArgsConstructor;

import java.util.ArrayList;
import java.util.List;

@Entity
@Table(name = "authors")
@Getter
@Setter
@NoArgsConstructor
public class Author {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @NotBlank
    @Column(nullable = false)
    private String name;

    @Column(unique = true)
    private String email;

    @Column(length = 1000)
    private String bio;

    @Column(name = "avatar_url")
    private String avatarUrl;

    @OneToMany(mappedBy = "author", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    private List<Article> articles = new ArrayList<>();

    public Author(String name, String email, String bio) {
        this.name = name;
        this.email = email;
        this.bio = bio;
    }

    public int getPublishedArticleCount() {
        return (int) articles.stream().filter(a -> a.getStatus() == Article.Status.PUBLISHED).count();
    }
}
