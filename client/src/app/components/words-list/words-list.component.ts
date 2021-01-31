import {Component, ElementRef, OnInit} from '@angular/core';
import WordService from '@app/services/word.service';
import {WordContext, WordsContext} from '@app/typing/app';
import {WordContextDialogComponent} from '@app/components/words-list/word-context-dialog/word-context-dialog.component';
import {MatDialog} from '@angular/material/dialog';

const WORD_PER_PAGE = 250;

@Component({
  selector: 'app-words-list',
  templateUrl: './words-list.component.html',
  styleUrls: ['./words-list.component.scss']
})
export class WordsListComponent implements OnInit {
  words: WordsContext = null;
  showLoader: boolean = true;
  page: number = 0;
  wordsCount: number;
  wordsPerPage: number = WORD_PER_PAGE;

  constructor(private wordService: WordService, private dialog: MatDialog) { }

  ngOnInit(): void {
    this.wordService.getWordsWithContext()
      .subscribe(
        res => {
          this.words = res.items;
          this.wordsCount = res.count;
        },
        err => console.error(err),
        () => this.showLoader = false,
    )
  }

  onWordClick($event, word: string, contextList: WordContext[]): void {
    const target = new ElementRef($event.currentTarget);

    this.dialog.open(WordContextDialogComponent, {
      data: { trigger: target, word, contextList },
      autoFocus: false,
    });
  }

}
